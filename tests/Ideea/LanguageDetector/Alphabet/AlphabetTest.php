<?php

/**
 * This file is part of the LanguageDetector package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Ideea\LanguageDetector\Alphabet;

/**
 * Alphabet testing
 */
class AlphabetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Alphabet
     */
    private $alphabet;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->alphabet = new Alphabet(
            'en',
            array(1, 2, 3),
            array(11, 12, 13),
            array(array(21, 22), array(31, 32))
        );
    }

    /**
     * Test has char in alphabet
     */
    public function testHasChar()
    {
        $this->assertTrue($this->alphabet->hasChar(1));
        $this->assertFalse($this->alphabet->hasChar(11));
    }

    /**
     * Test has common char in alphabet
     */
    public function testHasCommonChar()
    {
        $this->assertTrue($this->alphabet->hasCommonChar(11));
        $this->assertFalse($this->alphabet->hasCommonChar(1));
    }

    /**
     * Test Serialize/Unserialize
     */
    public function testSerialize()
    {
        $serialized = serialize($this->alphabet);

        $this->assertEquals($this->alphabet, unserialize($serialized));
    }
}
