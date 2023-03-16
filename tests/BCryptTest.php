<?php

namespace axy\crypt\tests;

use axy\crypt\BCrypt;

/**
 * coversDefaultClass axy\crypt\BCrypt
 */
class BCryptTest extends BaseTestCase
{
    /**
     * covers ::verify
     * @dataProvider providerVerify
     */
    public function testVerify(string $password, string $hash, bool $expected = true): void
    {
        $this->assertSame($expected, BCrypt::verify($password, $hash));
    }

    public static function providerVerify(): array
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
     */
    public function testHash(string $password, ?int $cost = null): void
    {
        $hash = BCrypt::hash($password, $cost);
        $this->assertIsString('string', $hash);
        $this->assertSame(60, strlen($hash));
        $this->assertTrue(BCrypt::verify($password, $hash));
    }

    public static function providerHash(): array
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
    public function testCreateSalt(): void
    {
        $salt = BCrypt::createSalt();
        $this->assertIsString('string', $salt);
        $pattern = '~^[A-Za-z0-9/.]{22}$~s';
        $this->assertMatchesRegularExpression($pattern, $salt);
    }
}
