<?php
/**
 * @package axy\crypt
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */

namespace axy\crypt\tests;

use axy\crypt\BCrypt;

/**
 * coversDefaultClass axy\crypt\BCrypt
 */
class BCryptTest extends \PHPUnit_Framework_TestCase
{
    /**
     * covers ::verify
     * @dataProvider providerVerify
     * @param string $password
     * @param string $hash
     * @param bool $expected [optional]
     */
    public function testVerify($password, $hash, $expected = true)
    {
        $this->assertSame($expected, BCrypt::verify($password, $hash));
    }

    /**
     * @return array
     */
    public function providerVerify()
    {
        return [
            ['password', '$2y$05$bsv3hXUdA2f0Ww8bTEWSjO6iKggo6TSX3rsMvwkZbwLRLxis6e/Zi'],
            ['password', '$2y$10$iE0N1/aoWHCpsIitgvMAs.zt693ezRTk9zQcZNeGesazu.qqq8/0i'],
            ['none', '$2y$05$bsv3hXUdA2f0Ww8bTEWSjO6iKggo6TSX3rsMvwkZbwLRLxis6e/Zi', false],
        ];
    }

    /**
     * covers ::hash
     * @dataProvider providerHash
     * @param string $password
     * @param int $cost [optional]
     */
    public function testHash($password, $cost = null)
    {
        $hash = BCrypt::hash($password, $cost);
        $this->assertInternalType('string', $hash);
        $this->assertSame(60, strlen($hash));
        $this->assertTrue(BCrypt::verify($password, $hash));
    }

    /**
     * @return array
     */
    public function providerHash()
    {
        return [
            ['password'],
            ['password', 8],
            ['long-password'],
            [''],
        ];
    }

    /**
     * covers ::convertAlphabets
     */
    public function testConvertAlphabets()
    {
        $base64 = 'Tb+LZN+GKIp8tkHvWOO4Sg==';
        $expected = 'RZ8JXL8EIGn6riFtUMM2Qe';
        $this->assertSame($expected, BCrypt::convertAlphabets($base64));
    }

    /**
     * covers ::createSalt
     */
    public function testCreateSalt()
    {
        $salt = BCrypt::createSalt();
        $this->assertInternalType('string', $salt);
        $pattern = '~^[A-Za-z0-9/\.]{22}$~s';
        $this->assertTrue((bool)preg_match($pattern, $salt));
    }
}
