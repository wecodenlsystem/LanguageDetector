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
 * Storage testing
 */
class StorageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Base testing
     */
    public function testBase()
    {
        $storage = new Storage();

        $alphabet = new Alphabet('en', array(), array(), array());

        $storage->add($alphabet);

        $this->assertEquals($alphabet, $storage->get('en'));

        $this->assertEquals(array(
            'en' => $alphabet
        ), $storage->all());

        $storage->remove($alphabet);

        $this->assertNull($storage->get('en'));
    }

    /**
     * Serialize/Unserialize testing
     */
    public function testSerialize()
    {
        $storage = new Storage();

        $alphabet = new Alphabet('en', array(), array(), array());

        $storage->add($alphabet);

        $serialized = serialize($storage);

        $this->assertEquals($storage, unserialize($serialized));
    }
}
