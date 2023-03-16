<?php

declare(strict_types=1);

namespace axy\crypt\tests;

use axy\crypt\APR1;

/**
 * coversDefaultClass axy\crypt\APR1
 */
class APR1Test extends BaseTestCase
{
    /**
     * covers ::createSalt
     */
    public function testCreateSalt(): void
    {
        $salt = APR1::createSalt();
        $this->assertIsString('string', $salt);
        $pattern = '~^[A-Za-z0-9./]{8}$~is';
        $this->assertMatchesRegularExpression($pattern, $salt);
    }

    /**
     * covers ::createSubHash
     * @dataProvider providerCreateSubHash
     */
    public function testCreateSubHash(string $string, string $salt, string $expected): void
    {
        $this->assertSame($expected, APR1::createSubHash($string, $salt));
    }

    public static function providerCreateSubHash(): array
    {
        return [
            ['one', 'IQ9IZAC8', 'wunOzzELYRnoT1g6Oj.ec0'],
            ['one', 'kvpZmkx6', 'zHyJ71iVSkoJxw4jOXpAu0'],
            ['two', 'CBIuqhN2', 'lYcwqwZkBBUKhshGYHA/P/'],
            ['this-is-a-Password', 'tj9CTOta', 'EivyGJIIa8Gwb5QFP.9dz1'],
        ];
    }

    /**
     * covers ::verifySubHash
     * @dataProvider providerVerifySubHash
     */
    public function testVerifySubHash(string $string, string $subHash, string $salt, bool $expected): void
    {
        $this->assertSame($expected, APR1::verifySubHash($string, $subHash, $salt));
    }

    public static function providerVerifySubHash(): array
    {
        return [
            ['this-is-a-Password', 'EivyGJIIa8Gwb5QFP.9dz1', 'tj9CTOta', true],
            ['this-is-a-Password', 'EivyGJIIa8Gwb5FFP.9dz1', 'tj9CTOta', false],
            ['this-is-a-Password', 'EivyGJIIa8Gwb5QFP.9dz1', 'tj9CT1ta', false],
            ['this-iS-a-Password', 'EivyGJIIa8Gwb5QFP.9dz1', 'tj9CTOta', false],
        ];
    }

    /**
     * covers ::verify
     * @dataProvider providerVerify
     */
    public function testVerify(string $string, string $hash, bool $expected): void
    {
        $this->assertSame($expected, APR1::verify($string, $hash));
    }

    public static function providerVerify(): array
    {
        return [
            ['this-is-a-Password', '$apr1$tj9CTOta$EivyGJIIa8Gwb5QFP.9dz1', true],
            ['this-is-a-Password', '$apr2$tj9CTOta$EivyGJIIa8Gwb5QFP.9dz1', false],
            ['this-is-a-Password', '$apr1$tj9CTOTa$EivyGJIIa8Gwb5QFP.9dz1', false],
            ['this-is-a-Password', '$apr1$tj9CTOta$EivyGJIIa8Gwb5qFP.9dz1', false],
            ['this-is-a-Password', 'none', false],
        ];
    }

    /**
     * covers ::hash
     * @dataProvider providerHash
     */
    public function testHash(string $string): void
    {
        $hash = APR1::hash($string);
        $this->assertIsString('string', $hash);
        $this->assertTrue(APR1::verify($string, $hash));
    }

    public static function providerHash(): array
    {
        return [
            ['one'],
            ['two'],
            ['three-four-five-six-seven'],
            [''],
            [md5(microtime())],
        ];
    }
}
