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
}
