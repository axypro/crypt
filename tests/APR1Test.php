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
}
