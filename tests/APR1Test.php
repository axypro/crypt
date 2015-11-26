<?php
/**
 * @package axy\crypt
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */

namespace axy\crypt\tests;

use axy\crypt\APR1;

/**
 * coversDefaultClass axy\crypt\APR1
 */
class APR1Test extends \PHPUnit_Framework_TestCase
{
    /**
     * covers ::createSalt
     */
    public function testCreateSalt()
    {
        $salt = APR1::createSalt();
        $this->assertInternalType('string', $salt);
        $pattern = '~^[A-Za-z0-9\./]{8}$~is';
        $this->assertRegExp($pattern, $salt);
    }

    /**
     * covers ::createSubHash
     * @dataProvider providerCreateSubHash
     * @param string $string
     * @param string $salt
     * @param string $expected
     */
    public function testCreateSubHash($string, $salt, $expected)
    {
        $this->assertSame($expected, APR1::createSubHash($string, $salt));
    }

    /**
     * @return string
     */
    public function providerCreateSubHash()
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
     * @param string $string
     * @param string $subHash
     * @param string $salt
     * @param bool $expected
     */
    public function testVerifySubHash($string, $subHash, $salt, $expected)
    {
        $this->assertSame($expected, APR1::verifySubHash($string, $subHash, $salt));
    }

    /**
     * @return string
     */
    public function providerVerifySubHash()
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
     * @param string $string
     * @param string $hash
     * @param bool $expected
     */
    public function testVerify($string, $hash, $expected)
    {
        $this->assertSame($expected, APR1::verify($string, $hash));
    }

    /**
     * @return string
     */
    public function providerVerify()
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
     * @param string $string
     */
    public function testHash($string)
    {
        $hash = APR1::hash($string);
        $this->assertInternalType('string', $hash);
        $this->assertTrue(APR1::verify($string, $hash));
    }

    /**
     * @return string
     */
    public function providerHash()
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
