<?php

declare(strict_types=1);

namespace axy\crypt;

/** Apache APR1-MD5 algorithm */
class APR1
{
    public const ALPHABET = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    public const BASE64_ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
    public const SALT_LENGTH = 8;
    public const PREFIX = '$apr1$';
    public const COUNT_STEPS = 1000;
    public const HASH_COUNT_STEPS = 5;

    /** Hashes a string */
    public static function hash(string $string): string
    {
        $salt = self::createSalt();
        return self::PREFIX . $salt . '$' . self::createSubHash($string, $salt);
    }

    /** Verifies a string hash */
    public static function verify(string $string, string $hash): bool
    {
        $pattern = '~^' . preg_quote(self::PREFIX) . '(?<salt>[A-Za-z0-9./]{8})\$(?<sub>[A-Za-z0-9./]+)$~is';
        if (!preg_match($pattern, $hash, $matches)) {
            return false;
        }
        return ($matches['sub'] === self::createSubHash($string, $matches['salt']));
    }

    /** Creates a random salt */
    public static function createSalt(): string
    {
        $alphabet = self::ALPHABET;
        $length = strlen($alphabet);
        $salt = [];
        for ($i = 0; $i < self::SALT_LENGTH; $i++) {
            $index = mt_rand(0, $length - 1);
            $salt[] = substr($alphabet, $index, 1);
        }
        return implode('', $salt);
    }

    /** Creates a hash for a string and a salt */
    public static function createSubHash(string $string, string $salt): string
    {
        $context = self::createContext($string, $salt);
        $null = chr(0);
        $hash = '';
        for ($i = 0; $i < self::HASH_COUNT_STEPS; $i++) {
            $k = $i + 6;
            $j = $i + 12;
            if ($j === 16) {
                $j = 5;
            }
            $hash = $context[$i] . $context[$k] . $context[$j] . $hash;
        }
        $hash = $null . $null . $context[11] . $hash;
        $hash = base64_encode($hash);
        $hash = substr($hash, 2);
        $hash = strrev($hash);
        $hash = strtr($hash, self::BASE64_ALPHABET, self::ALPHABET);
        return $hash;
    }

    /** Verifies that a string matches a subHash + salt */
    public static function verifySubHash(string $string, string $subHash, string $salt): bool
    {
        return ($subHash === self::createSubHash($string, $salt));
    }

    private static function createContext(string $string, string $salt): string
    {
        $len = strlen($string);
        $null = chr(0);
        $context = $string . self::PREFIX . $salt;
        $binary = pack('H32', md5("$string$salt$string"));
        for ($i = $len; $i > 0; $i -= 16) {
            $context .= substr($binary, 0, min(16, $i));
        }
        for ($i = $len; $i > 0; $i >>= 1) {
            $context .= ($i & 1) ? $null : $string[0];
        }
        $context = pack('H32', md5($context));
        return self::iterateContext($string, $salt, $context);
    }

    private static function iterateContext(string $string, string $salt, string $context): string
    {
        for ($i = 0; $i < self::COUNT_STEPS; $i++) {
            $value = [];
            if ($i % 2) {
                $value[] = $string;
            } else {
                $value[] = $context;
            }
            if ($i % 3) {
                $value[] = $salt;
            }
            if ($i % 7) {
                $value[] = $string;
            }
            if ($i % 2) {
                $value[] = $context;
            } else {
                $value[] = $string;
            }
            $context = pack('H32', md5(implode('', $value)));
        }
        return $context;
    }
}
