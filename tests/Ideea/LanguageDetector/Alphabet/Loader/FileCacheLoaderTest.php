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
 * File cache loader testing
 */
class FileCacheLoaderTest extends YamlLoaderTest
{
    /**
     * @var YamlLoader
     */
    private $yamlLoader;

    /**
     * @var string
     */
    private $cacheDirectory;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->cacheDirectory = sys_get_temp_dir();
        $this->yamlLoader = new YamlLoader();
        $this->loader = new FileCacheLoader($this->yamlLoader, $this->cacheDirectory);
    }

    /**
     * Testing load
     */
    public function testLoad()
    {
        parent::testLoad();

        $fileHash = md5(__DIR__ . $this->alphabetFile);

        $this->assertTrue(file_exists($this->cacheDirectory . '/' . $fileHash . '.cache'));
    }
}
