<?php
/**
 * LoginClient
 *
 * @category Class
 *
 * @author   Authress Developers
 *
 * @link     https://authress.io/app/#/api
 */

namespace AuthressSdk\Login;

use AuthressSdk\ApiException;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\CookieJarInterface;
use GuzzleHttp\Exception\RequestException;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\Constraint;

/**
 * LoginClient Class Doc Comment
 *
 * @category Class
 *
 * @author   Authress Developers
 *
 * @link     https://authress.io/app/#/api
 */
class LoginClient
{
    private static array $jwkCache = [];
    /**
     * @var ClientInterface
     */
    protected $client;
    /**
     * @var \GuzzleHttp\Cookie\CookieJarInterface|\GuzzleHttp\Cookie\CookieJar
     */
    protected CookieJarInterface $cookieJar;

    private string $authressLoginHostUrl;

    private string $applicationId;

    public function __construct(string $authressLoginHostUrl, string $applicationId)
    {
        $this->authressLoginHostUrl = $authressLoginHostUrl;
        $this->applicationId = $applicationId;

        $this->cookieJar = new CookieJar();

        $this->client = new Client(
            [
                'base_uri' => $this->authressLoginHostUrl,
                'cookies' => $this->cookieJar,
            ]
        );
    }

    /**
     * unlinkIdentity
     *
     * Unlink an identity from the user's account. Throws an error if identity cannot be unlinked.
     *
     * @param  string Specify the provider connection id that user would like to unlink - see https://authress.io/app/#/manage?focus=connections (required)
     *
     * @throws InvalidArgumentException
     * @throws \AuthressSdk\ApiException
     */
    public function unlinkIdentity(string $connectionId)
    {
        $userSessionExists = $this->userSessionExists();
        $token = $this->getToken();
        if (!$userSessionExists || !$token) {
            throw new Exception("User must be logged into an existing account before linking a second account.");
        }

        if ($connectionId === null) {
            throw new InvalidArgumentException("connectionId must be specified");
        }

        try {
            $response = $this->client->request(
                'DELETE',
                '/api/identities/' . $connectionId,
                [
                    'headers' => [
                        'Cookie' => 'authress-session=' . ($_COOKIE["authress-session"] ?? ''),
                        'Authorization' => 'Bearer ' . $token,
                        'User-Agent' => 'PHP AuthressSDK',
                        'Origin' => $this->authressLoginHostUrl
                    ]
                ]
            );
            $statusCode = $response->getStatusCode();

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API',
                        $statusCode
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    $response->getBody()
                );
            }
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
     */
    public function userSessionExists(): bool
    {
        $authRequest = $_SESSION["authenticationRequest"] ?? null;

        // Your app was redirected to from the Authress Hosted Login page. The next step is to show the user the login widget and enable them to login.
        $state = $_GET['state'] ?? null;
        $flow = $_GET['flow'] ?? null;
        if ($state !== null && $flow === 'oauthLogin') {
            return false;
        }

        $userData = $this->getUserIdentity();
        // User is already logged in
        if (isset($userData)) {
            return true;
        }

        $authCode = $_GET['code'] ?? null;
        $nonce = isset($authRequest) && isset($authRequest->nonce) ? $authRequest->nonce : null;
        if (isset($authCode) && isset($nonce)) {
            try {
                $response = $this->client->request(
                    'POST',
                    '/api/authentication/' . $nonce . '/tokens',
                    [
                        'headers' => [
                            'User-Agent' => 'PHP AuthressSDK',
                            'Origin' => $this->authressLoginHostUrl
                        ],
                        'json' => [
                            'code' => $authCode,
                            'grant_type' => 'authorization_code',
                            'redirect_uri' => $authRequest->redirectUrl,
                            'code_verifier' => $authRequest->codeVerifier,
                            'client_id' => $this->applicationId
                        ]
                    ]
                );

                $json = json_decode($response->getBody());
                $idToken = $json->id_token ?? null;
                $accessTokenCookie = $this->cookieJar->getCookieByName('authorization') !== null ? $this->cookieJar->getCookieByName('authorization')->getValue() : null;
                $accessToken = $json->access_token ?? $accessTokenCookie;
                $_SESSION['authorization'] = $accessToken;
                $_SESSION['user'] = $idToken;
                unset($_SESSION["authenticationRequest"]);
                return true;
            } catch (Exception $e) {
                unset($_SESSION["authenticationRequest"]);
                throw $e;
            }
        }

        try {
            $response = $this->client->request(
                'GET',
                '/api/session',
                [
                    'headers' => [
                        'Cookie' => 'authress-session=' . ($_COOKIE["authress-session"] ?? ''),
                        'User-Agent' => 'PHP AuthressSDK',
                        'Origin' => $this->authressLoginHostUrl
                    ]
                ]
            );
            $authressSession = $this->cookieJar->getCookieByName('authress-session') !== null ? $this->cookieJar->getCookieByName('authress-session')->getValue() : null;
            setcookie(
                'authress-session',
                $authressSession,
                [
                    'expires' => time() + 60 * 60 * 24 * 90,
                    'domain' => str_replace("https://", "", $this->authressLoginHostUrl),
                    'path' => '/',
                    'httponly' => true,
                    'secure' => true,
                    'samesite' => 'Strict'
                ]
            );

            $json = json_decode($response->getBody());
            $idToken = $json->id_token ?? null;
            $accessTokenCookie = $this->cookieJar->getCookieByName('authorization') !== null ? $this->cookieJar->getCookieByName('authorization')->getValue() : null;
            $accessToken = $json->access_token ?? $accessTokenCookie;
            $_SESSION['authorization'] = $accessToken;
            $_SESSION['user'] = $idToken;
            if (!isset($idToken) || $idToken === null || $idToken === '') {
                return false;
            }

            return true;
        } catch (Exception $e) {
            if ($e->getCode() !== 403 && $e->getCode() !== 404) {
                throw $e;
            }
        }

        return false;
    }

    /**
     * @description Gets the user's profile data and returns it if it exists. Should be called after {@link userSessionExists} or it will be empty.
     *
     * @return object The user data object.
     */
    public function getUserIdentity()
    {
        $idToken = $_SESSION['user'] ?? null;
        if ($idToken === null) {
            return null;
        }
        $decoded = json_decode(base64_decode(strtr($this->decodeToken($idToken)->toString(), '-_', '+/')));
        if ($decoded->exp < time()) {
            return null;
        }
        return $decoded;
    }

    private function decodeToken(string $token)
    {
        $config = Configuration::forUnsecuredSigner();
        $token = $config->parser()->parse($token);
        return $token->claims();
    }

    /**
     * getToken
     *
     * Returns the user's bearer token if it exists.
     *
     * @return string the authorization token ready to be used if it exists.
     */
    public function getToken(): ?string
    {
        return $_SESSION['authorization'] ?? null;
    }

    /**
     * linkIdentity
     *
     * Link a new identity to the currently logged in user. The user will be asked to authenticate to a new connection. This will navigate the user to their selected connection/provider and then redirect back to the LinkIdentityParameters.redirectUrl.
     *
     * @param \AuthressSdk\Login\LinkIdentityParameters $linkIdentityParameters
     *
     * @throws \AuthressSdk\ApiException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function linkIdentity(LinkIdentityParameters $linkIdentityParameters)
    {
        $userSessionExists = $this->userSessionExists();
        $token = $this->getToken();
        if (!$userSessionExists || !$token) {
            throw new Exception("User must be logged into an existing account before linking a second account.");
        }

        if ($linkIdentityParameters->connectionId === null && $linkIdentityParameters->tenantLookupIdentifier === null) {
            throw new InvalidArgumentException("connectionId or tenantLookupIdentifier must be specified");
        }

        $codeVerifier = Pkce::generateCodeVerifier();
        $codeChallenge = Pkce::generateCodeChallenge($codeVerifier);

        $redirectUrl = $linkIdentityParameters->redirectUrl ?? $this->getCurrentUrl();

        try {
            $response = $this->client->request(
                'POST',
                '/api/authentication',
                [
                    'headers' => [
                        'Cookie' => 'authress-session=' . ($_COOKIE["authress-session"] ?? ''),
                        'Authorization' => 'Bearer ' . $token,
                        'User-Agent' => 'PHP AuthressSDK',
                        'Origin' => $this->authressLoginHostUrl
                    ],
                    'json' => [
                        'linkIdentity' => true,
                        'redirectUrl' => $redirectUrl,
                        'codeChallengeMethod' => 'S256',
                        'codeChallenge' => $codeChallenge,
                        'connectionId' => $linkIdentityParameters->connectionId,
                        'tenantLookupIdentifier' => $linkIdentityParameters->tenantLookupIdentifier,
                        'applicationId' => $this->applicationId
                    ]
                ]
            );
            $statusCode = $response->getStatusCode();

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API',
                        $statusCode
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    $response->getBody()
                );
            }

            $json = json_decode($response->getBody());
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

    private function getCurrentUrl()
    {
        $s = $_SERVER;
        $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on');
        $sp = strtolower($s['SERVER_PROTOCOL']);
        $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
        $port = $s['SERVER_PORT'];
        $port = ((!$ssl && $port == '80') || ($ssl && $port == '443')) ? '' : ':' . $port;
        $host = $s['HTTP_HOST'] ?? null;
        $host = $host ?? $s['SERVER_NAME'] . $port;

        // Remove specific parameter from query string
        $filteredURL = $_SERVER['REQUEST_URI'];
        $filteredURL = preg_replace('~(\?|&)' . 'iss' . '=[^&]*~', '$1', $filteredURL);
        $filteredURL = preg_replace('~(\?|&)' . 'nonce' . '=[^&]*~', '$1', $filteredURL);
        $filteredURL = preg_replace('~(\?|&)' . 'code' . '=[^&]*~', '$1', $filteredURL);
        $filteredURL = preg_replace('~(\?|&)' . 'state' . '=[^&]*~', '$1', $filteredURL);
        $filteredURL = preg_replace('~(\?|&)' . 'access_token' . '=[^&]*~', '$1', $filteredURL);
        $filteredURL = preg_replace('~(\?|&)' . 'id_token' . '=[^&]*~', '$1', $filteredURL);
        $filteredURL = preg_replace('~(\?|&)' . 'expires_in' . '=[^&]*~', '$1', $filteredURL);

        return $protocol . '://' . $host . $filteredURL;
    }

    /**
     * authenticate
     *
     * Logs a user in, if the user is not logged in, will redirect the user to their selected connection/provider and then redirect back to the authenticationParameters.redirectUrl
     *
     * @param \AuthressSdk\Login\AuthenticationParameters $authenticationParameters Parameters for controlling how and when users should be authenticated for the app. (required)
     *
     * @throws \InvalidArgumentException
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

        $redirectUrl = $authenticationParameters->redirectUrl ?? $this->getCurrentUrl();

        try {
            $response = $this->client->request(
                'POST',
                '/api/authentication',
                [
                    'headers' => [
                        'User-Agent' => 'PHP AuthressSDK',
                        'Origin' => $this->authressLoginHostUrl
                    ],
                    'json' => [
                        'redirectUrl' => $redirectUrl,
                        'codeChallengeMethod' => 'S256',
                        'codeChallenge' => $codeChallenge,
                        'connectionId' => $authenticationParameters->connectionId,
                        'tenantLookupIdentifier' => $authenticationParameters->tenantLookupIdentifier,
                        'applicationId' => $this->applicationId,
                        'responseLocation' => 'query',
                        'flowType' => 'code'
                    ]
                ]
            );
            $statusCode = $response->getStatusCode();

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API',
                        $statusCode
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    $response->getBody()
                );
            }

            $json = json_decode($response->getBody());

            $_SESSION["authenticationRequest"] = (object)[
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

    public function verifyToken(?string $overrideToken = null)
    {
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
            return (object)$token->claims()->all();
        } catch (RequiredConstraintsViolated $e) {
            throw new Exception("Unauthorized");
        } catch (Exception $e) {
            throw new Exception("Unexpected exception verifying user token");
        }
    }

    private function getJwk($kid)
    {
        if (isset(self::$jwkCache[$kid])) {
            return self::$jwkCache[$kid];
        }

        if (isset($_SESSION['jwk'])) {
            return $_SESSION['jwk'];
        }

        $expectedIss = $this->authressLoginHostUrl;
        $client = new Client(['base_uri' => $expectedIss, 'decode_content' => false]);

        $response = $client->request('GET', '/.well-known/openid-configuration/jwks');
        $keys = json_decode($response->getBody()->getContents())->keys;

        $jwk = null;
        foreach ($keys as $element) {
            if ($kid !== $element->kid) {
                continue;
            }

            $jwk = InMemory::plainText(base64_decode(strtr($element->x, '-_', '+/')), true);
        }

        if (empty($jwk) || $jwk === null) {
            throw new Exception("Unauthorized");
        }
        self::$jwkCache[$kid] = $jwk;
        return $_SESSION['jwk'] = $jwk;
        return $jwk;
    }
}
