<?php
/**
 * @package axy\crypt
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */

namespace axy\crypt;

/**
 * Apache APR1-MD5 algorithm.
 */
class APR1
{
    const ALPHABET = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    const SALT_LENGTH = 8;

    /**
     * Creates a random salt
     *
     * @return string
     */
    public static function createSalt()
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
}
