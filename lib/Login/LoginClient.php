<?php
/**
 * LoginClient

 * @category Class
 * @package  AuthressSdk
 * @author   Authress Developers
 * @link     https://authress.io/app/#/api
 */


namespace AuthressSdk\Login;

use AuthressSdk\ApiException;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;

use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\UnencryptedToken;
use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Validation\Constraint;
use Lcobucci\JWT\Signer\Key\InMemory;

/**
 * LoginClient Class Doc Comment
 *
 * @category Class
 * @package  AuthressSdk
 * @author   Authress Developers
 * @link     https://authress.io/app/#/api
 */
class LoginClient
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var string
     */
    private string $authressLoginHostUrl;

    /**
     * @var string
     */
    private string $applicationId;

    private static array $jwkCache = [];

    /**
     * @param string   $authressLoginHostUrl
     */
    public function __construct(string $authressLoginHostUrl, string $applicationId) {
        $this->authressLoginHostUrl = $authressLoginHostUrl;
        $this->applicationId = $applicationId;

        $this->client = new Client([
            'base_uri' => $this->authressLoginHostUrl,
            'cookies' => true
        ]);
    }

    private function getCurrentUrl()
    {
        $s = $_SERVER;
        $ssl      = ( ! empty( $s['HTTPS'] ) && $s['HTTPS'] == 'on' );
        $sp       = strtolower( $s['SERVER_PROTOCOL'] );
        $protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
        $port     = $s['SERVER_PORT'];
        $port     = ( ( ! $ssl && $port == '80' ) || ( $ssl && $port == '443' ) ) ? '' : ':' . $port;
        $host     = isset( $s['HTTP_HOST'] ) ? $s['HTTP_HOST'] : null;
        $host     = isset( $host ) ? $host : $s['SERVER_NAME'] . $port;

        // Remove specific parameter from query string
        $filteredURL = $_SERVER['REQUEST_URI'];
        $filteredURL = preg_replace('~(\?|&)' . 'iss' . '=[^&]*~', '$1', $filteredURL);
        $filteredURL = preg_replace('~(\?|&)' . 'nonce' . '=[^&]*~', '$1', $filteredURL);
        $filteredURL = preg_replace('~(\?|&)' . 'access_token' . '=[^&]*~', '$1', $filteredURL);
        $filteredURL = preg_replace('~(\?|&)' . 'id_token' . '=[^&]*~', '$1', $filteredURL);
        $filteredURL = preg_replace('~(\?|&)' . 'expires_in' . '=[^&]*~', '$1', $filteredURL);

        return $protocol . '://' . $host . $filteredURL;
    }


    /**
     * authenticate
     * 
     * Logs a user in, if the user is not logged in, will redirect the user to their selected connection/provider and then redirect back to the authenticationParameters.redirectUrl
     * @param  AuthressSdk\Login\AuthenticationParameters $authenticationParameters Parameters for controlling how and when users should be authenticated for the app. (required)
     * @throws InvalidArgumentException
     * @return boolean
     */
    public function authenticate(AuthenticationParameters $authenticationParameters): bool
    {
        if (!$authenticationParameters->force) {
            $userSessionExists = $this->userSessionExists();
            if ($userSessionExists) {
                return true;
            }
        }

        if ($authenticationParameters->connectionId === null && $authenticationParameters->tenantLookupIdentifier === null) {
            throw new InvalidArgumentException("connectionId or tenantLookupIdentifier must be specified");
        }

        $codeVerifier = Pkce::generateCodeVerifier();
        $codeChallenge = Pkce::generateCodeChallenge($codeVerifier);

        $redirectUrl = isset($authenticationParameters->redirectUrl) ? $authenticationParameters->redirectUrl : $this->getCurrentUrl();

        try {
            $response = $this->client->request('POST', '/api/authentication', [
                'headers' => [
                    'User-Agent' => 'PHP AuthressSDK',
                    'Origin' => $this->getCurrentUrl()
                ],
                'json' => [
                    'redirectUrl' => $redirectUrl,
                    'codeChallengeMethod' => 'S256',
                    'codeChallenge' => $codeChallenge,
                    'connectionId' => $authenticationParameters->connectionId,
                    'tenantLookupIdentifier' => $authenticationParameters->tenantLookupIdentifier,
                    'applicationId' => $this->applicationId
                ]
            ]);
            $statusCode = $response->getStatusCode();
    
            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    $response->getBody()
                );
            }

            $json = json_decode($response->getBody());

            $_SESSION["authenticationRequest"] = [
                'nonce' => $json->authenticationRequestId,
                'codeVerifier' => $codeVerifier,
                'lastConnectionId' => $authenticationParameters->connectionId,
                'tenantLookupIdentifier' => $authenticationParameters->tenantLookupIdentifier,
                'redirectUrl' => $redirectUrl
            ];
            header("Location: " . $json->authenticationUrl);
            exit();
        } catch (RequestException $e) {
            throw new ApiException(
                "[{$e->getCode()}] {$e->getMessage()}",
                $e->getCode(),
                $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null
            );
        }
    }

    /**
     * userSessionExists
     *
     * Call this function on every route change. It will check if the user just logged in or is still logged in.
     * @return boolean
     */
    public function userSessionExists(): bool
    {
        $authRequest = isset($_SESSION["authenticationRequest"]) ? $_SESSION["authenticationRequest"] : null;
        if ($authRequest !== null) {
            unset($_SESSION["authenticationRequest"]);
        }

        // Your app was redirected to from the Authress Hosted Login page. The next step is to show the user the login widget and enable them to login.
        $state = isset($_GET['state']) ? $_GET['state'] : null;
        $flow = isset($_GET['flow']) ? $_GET['flow'] : null;
        if ($state !== null && $flow === 'oauthLogin') {
            return false;
        }

        if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) {
            $accessToken = isset($_GET['access_token']) ? $_GET['access_token'] : null;
            if ($accessToken !== null) {
                $idToken = isset($_GET['id_token']) ? $_GET['id_token'] : null;
                $decodedIdToken = $this->decodeToken($idToken);
                setcookie('authorization', $accessToken, $decodedIdToken->get('exp')->getTimestamp(), '/');
                setcookie('user', $idToken, $decodedIdToken->get('exp')->getTimestamp(), '/');
                return true;
            }
            // Otherwise check cookies and then force the user to log in
        }

        $userData = $this->getUserIdentity();
        // User is already logged in
        if ($userData !== null) {
            return true;
        }

        if (strpos($_SERVER['HTTP_HOST'], 'localhost') === false) {
            try {
                $this->client->request('GET', '/api/session');
                $cookieJar = $this->client->getConfig('cookies');
                $accessToken = $cookieJar->getCookieByName('authorization')->getValue();
                $idToken = $cookieJar->getCookieByName('user')->getValue();
                $decodedIdToken = $this->decodeToken($idToken);
                setcookie('authorization', $accessToken, [
                    'expires' => $decodedIdToken->get('exp')->getTimestamp(),
                    'path' => '/',
                    'secure' => true,
                    'samesite' => 'Strict'
                ]);
                setcookie('user', $idToken, [
                    'expires' => $decodedIdToken->get('exp')->getTimestamp(),
                    'path' => '/',
                    'secure' => true,
                    'samesite' => 'Strict'
                ]);
            } catch (\Exception $e) {
                if ($e->getCode() !== 403 && $e->getCode() !== 404) {
                    throw $e;
                }
            }

            $userData = $this->getUserIdentity();
            // User is now logged in
            if ($userData !== null) {
                return true;
            }
        }
        return false;
    }

    /**
     * getToken
     *
     * Returns the user's bearer token if it exists.
     * @return string the authorization token ready to be used if it exists.
     */
    public function getToken(): ?string
    {
        $accessToken = isset($_COOKIE['authorization']) ? $_COOKIE['authorization'] : (isset($_GET) && isset($_GET['access_token']) ? $_GET['access_token'] : null);
        return $accessToken;
    }

    /**
    * @description Gets the user's profile data and returns it if it exists. Should be called after {@link userSessionExists} or it will be empty.
    * @return object The user data object.
    */
    public function getUserIdentity() {
        $idToken = isset($_COOKIE['user']) ? $_COOKIE['user'] : (isset($_GET) && isset($_GET['id_token']) ? $_GET['id_token'] : null);
        if ($idToken === null) {
            return null;
        }
        return json_decode(base64_decode(strtr($this->decodeToken($idToken)->toString(), '-_', '+/')));
    }

    private function decodeToken(string $token) {
        $config = Configuration::forUnsecuredSigner();
		$token = $config->parser()->parse($token);
        return $token->claims();
    }

	public function verifyToken(?string $overrideToken = null) {
        $token = $overrideToken ?? $this->getToken();
		$expectedIss = $this->authressLoginHostUrl;

		$config = Configuration::forUnsecuredSigner();
		$token = $config->parser()->parse($token);
		$keyId = $token->headers()->get('kid');

		$jwk = $this->getJwk($keyId);

		$config->setValidationConstraints(new Constraint\LooseValidAt(SystemClock::fromUTC()));
		$config->setValidationConstraints(new Constraint\IssuedBy($expectedIss));
        $signer = new Signer\Eddsa();
		$config->setValidationConstraints(new Constraint\SignedWith($signer, $jwk));
		$constraints = $config->validationConstraints();

		try {
			$config->validator()->assert($token, ...$constraints);
			$userObject = (object) $token->claims()->all();
			return $userObject;
		} catch (RequiredConstraintsViolated $e) {
			throw new \Exception("Unauthorized");
		} catch (\Exception $e) {
			throw new \Exception("Unexpected exception verifying user token");
		}
	}

    private function getJwk($kid) {
        if (isset(self::$jwkCache[$kid])) {
            return self::$jwkCache[$kid];
        }

        if (isset($_SESSION['jwk'])) {
            return $_SESSION['jwk'];
        }

        $expectedIss = $this->authressLoginHostUrl;
        $client = new Client([ 'base_uri' => $expectedIss, 'decode_content' => false ]);

		$response = $client->request('GET', '/.well-known/openid-configuration/jwks');
		$keys = json_decode($response->getBody()->getContents())->keys;

		$jwk = null;
		foreach ( $keys as $element ) {
			if ( $kid !== $element->kid ) {
				continue;
			}

            $jwk = InMemory::plainText(base64_decode(strtr($element->x, '-_', '+/')), true);
		}

		if (empty($jwk) || $jwk === null) {
			throw new \Exception("Unauthorized");
		}
        self::$jwkCache[$kid] = $jwk;
        return $_SESSION['jwk'] = $jwk;
        return $jwk;
    }
}