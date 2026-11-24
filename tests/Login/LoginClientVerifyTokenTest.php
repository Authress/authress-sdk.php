<?php

namespace AuthressSdk\Login;

use DateTimeImmutable;
use Exception;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Signer\Key\InMemory;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

class JwksStubLoginClient extends LoginClient
{
    public int $jwksFetchCount = 0;

    public function __construct(string $host, private array $jwks)
    {
        parent::__construct($host, 'app_test');
    }

    protected function fetchJwks(): array
    {
        $this->jwksFetchCount++;
        return $this->jwks;
    }
}

class LoginClientVerifyTokenTest extends TestCase
{
    private const HOST = 'https://authress.company.com';

    protected function setUp(): void
    {
        (new ReflectionProperty(LoginClient::class, 'jwkCache'))->setValue(null, []);
        unset($_SESSION['jwk'], $_SESSION['authress_jwk']);
    }

    private function newKeyPair(): array
    {
        $keyPair = sodium_crypto_sign_keypair();
        return [sodium_crypto_sign_secretkey($keyPair), sodium_crypto_sign_publickey($keyPair)];
    }

    private function jwk(string $kid, string $publicKey): object
    {
        return (object) ['kid' => $kid, 'alg' => 'EdDSA', 'x' => rtrim(strtr(base64_encode($publicKey), '+/', '-_'), '=')];
    }

    private function token(string $kid, string $secretKey, string $issuer, string $expiresIn = '+1 hour'): string
    {
        $config = Configuration::forSymmetricSigner(new Signer\Eddsa(), InMemory::plainText($secretKey, true));
        $now = new DateTimeImmutable();
        return $config->builder()
            ->issuedBy($issuer)
            ->relatedTo('user_alice')
            ->issuedAt($now->modify('-2 hours'))
            ->expiresAt($now->modify($expiresIn))
            ->withHeader('kid', $kid)
            ->getToken($config->signer(), $config->signingKey())
            ->toString();
    }

    public function testValidTokenIsAccepted(): void
    {
        [$secretKey, $publicKey] = $this->newKeyPair();
        $client = new JwksStubLoginClient(self::HOST, [$this->jwk('kid-valid', $publicKey)]);

        $claims = $client->verifyToken($this->token('kid-valid', $secretKey, self::HOST));

        $this->assertSame('user_alice', $claims->sub);
    }

    public function testExpiredTokenIsRejected(): void
    {
        [$secretKey, $publicKey] = $this->newKeyPair();
        $client = new JwksStubLoginClient(self::HOST, [$this->jwk('kid-expired', $publicKey)]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Unauthorized');
        $client->verifyToken($this->token('kid-expired', $secretKey, self::HOST, '-1 hour'));
    }

    public function testTokenFromAnotherIssuerIsRejected(): void
    {
        [$secretKey, $publicKey] = $this->newKeyPair();
        $client = new JwksStubLoginClient(self::HOST, [$this->jwk('kid-issuer', $publicKey)]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Unauthorized');
        $client->verifyToken($this->token('kid-issuer', $secretKey, 'https://attacker.example'));
    }

    public function testHostWithTrailingSlashesStillMatchesTheIssuer(): void
    {
        [$secretKey, $publicKey] = $this->newKeyPair();
        $client = new JwksStubLoginClient(self::HOST . '//', [$this->jwk('kid-slash', $publicKey)]);

        $claims = $client->verifyToken($this->token('kid-slash', $secretKey, self::HOST));

        $this->assertSame('user_alice', $claims->sub);
    }

    public function testKeyCachedForOneKeyIdIsNotUsedForAnotherKeyId(): void
    {
        [$firstSecretKey, $firstPublicKey] = $this->newKeyPair();
        [$secondSecretKey, $secondPublicKey] = $this->newKeyPair();
        $client = new JwksStubLoginClient(self::HOST, [$this->jwk('kid-first', $firstPublicKey), $this->jwk('kid-second', $secondPublicKey)]);

        $client->verifyToken($this->token('kid-first', $firstSecretKey, self::HOST));
        $claims = $client->verifyToken($this->token('kid-second', $secondSecretKey, self::HOST));

        $this->assertSame('user_alice', $claims->sub);
    }

    public function testKeysAreFetchedOncePerKeyId(): void
    {
        [$secretKey, $publicKey] = $this->newKeyPair();
        $client = new JwksStubLoginClient(self::HOST, [$this->jwk('kid-once', $publicKey)]);

        $client->verifyToken($this->token('kid-once', $secretKey, self::HOST));
        $client->verifyToken($this->token('kid-once', $secretKey, self::HOST));

        $this->assertSame(1, $client->jwksFetchCount);
    }
}
