<?php

declare(strict_types=1);

namespace AuthressSdk\Login;

use Exception;

/**
 * Class PKCE.
 */
final class Pkce
{
    /**
     * Generate a random string of between 43 and 128 characters containing
     * letters, numbers and "-", ".", "_", "~", as defined in the RFC 7636
     * specification.
     *
     * @link https://tools.ietf.org/html/rfc7636
     */
    public static function generateCodeVerifier(): string
    {
        $string = '';

        while (($len = mb_strlen($string)) < 128) {
            $size = 128 - $len;
            $size = max($size, 1);

            try {
                $bytes = random_bytes($size);
            } catch (Exception $exception) {
                $bytes = (string)openssl_random_pseudo_bytes($size);
            }

            $string .= mb_substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }

        return $string;
    }

    /**
     * Returns the generated code challenge from the given code_verifier. The
     * code_challenge should be a Base64 encoded string with URL and
     * filename-safe characters. The trailing '=' characters should be removed
     * and no line breaks, whitespace, or other additional characters should be
     * present.
     *
     * @param string $codeVerifier String to generate code challenge from.
     */
    public static function generateCodeChallenge(string $codeVerifier): string
    {
        $encoded = base64_encode(hash('sha256', $codeVerifier, true));
        return strtr(rtrim($encoded, '='), '+/', '-_');
    }
}
