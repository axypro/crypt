<?php

declare(strict_types=1);

namespace axy\crypt;

use axy\random\Random;
use axy\binary\Binary;

/**
 * Blowfish
 */
class BCrypt
{
    public const ALPHABET = './ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    public const BASE64_ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
    public const SALT_START_LENGTH = 16;
    public const SALT_FINAL_LENGTH = 22;
    public const PREFIX = "2y";
    public const DEFAULT_COST = 5;

    /** Hash a password */
    public static function hash(string $password, ?int $cost = null): string
    {
        if ($cost === null) {
            $cost = self::DEFAULT_COST;
        }
        $prefix = sprintf('$%s$%02d$', self::PREFIX, $cost);
        $salt = self::createSalt();
        return crypt($password, $prefix . $salt);
    }

    /** Verifies a password */
    public static function verify(string $password, string $hash): bool
    {
        $salt = Binary::getSlice($hash, 0, self::SALT_FINAL_LENGTH + 7);
        return (crypt($password, $salt) === $hash);
    }

    /** Convert Base64 string to BCrypt string */
    public static function convertAlphabets(string $base64): string
    {
        $base64 = rtrim($base64, '=');
        return strtr($base64, self::BASE64_ALPHABET, self::ALPHABET);
    }

    /** Creates a salt */
    public static function createSalt(): string
    {
        $salt = Random::createString(self::SALT_START_LENGTH);
        $base64 = base64_encode($salt);
        $salt = self::convertAlphabets($base64);
        return Binary::getSlice($salt, 0, self::SALT_FINAL_LENGTH);
    }
}
