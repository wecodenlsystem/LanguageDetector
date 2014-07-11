<?php

/**
 * This file is part of the LanguageDetector package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Ideea\LanguageDetector\Alphabet\Loader;

/**
 * Yaml loader testing
 */
class YamlLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var YamlLoader
     */
    protected $loader;

    /**
     * @var string
     */
    protected $alphabetFile = '/../../../../data/alphabets.yml';

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->loader = new YamlLoader();
    }

    /**
     * Load test
     */
    public function testLoad()
    {
        $file = __DIR__ . $this->alphabetFile;

        $alphabets = $this->loader->load($file);

        $this->assertInstanceOf('Ideea\LanguageDetector\Alphabet\Storage', $alphabets);

        $this->assertCount(2, $alphabets);

        $enAlphabet = $alphabets->get('en');

        $this->assertEquals(array(65, 97, 66, 98, 67, 99), $enAlphabet->getCharCodes());
        $this->assertEquals(array(), $enAlphabet->getCommonCharCodes());
        $this->assertEquals(array(), $enAlphabet->getMultipleCharCodes());

        $esAlphabet = $alphabets->get('es');

        $this->assertEquals(array(65, 97, 66, 98, 67, 99), $esAlphabet->getCharCodes());
        $this->assertEquals(array(68, 100, 69, 101), $esAlphabet->getCommonCharCodes());
        $this->assertEquals(array(
            array(67, 104),
            array(99, 104),
            array(69, 115),
            array(101, 115)
        ), $esAlphabet->getMultipleCharCodes());
    }
}
