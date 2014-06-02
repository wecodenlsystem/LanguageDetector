<?php

/**
 * This file is part of the LanguageDetector package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Ideea\LanguageDetector\Section\Loader;

/**
 * Caching loads resources
 */
class FileCacheLoader implements LoaderInterface
{
    /**
     * Really loader
     *
     * @param LoaderInterface
     */
    private $reallyLoader;

    /**
     * @var string
     */
    private $cacheDir;

    /**
     * Construct
     *
     * @param LoaderInterface $loader
     * @param string          $cacheDir
     */
    public function __construct(LoaderInterface $loader, $cacheDir)
    {
        $this->reallyLoader = $loader;
        $this->cacheDir = $cacheDir;
    }

    /**
     * {@inheritDoc}
     */
    public function load($path)
    {
        $fileHash = md5($path);
        $cacheFile = $this->cacheDir . '/' . $fileHash . '.cache';

        if (is_file($cacheFile)) {
            // Cache file already exists. Loads from cache
            $content = file_get_contents($cacheFile);
            return unserialize($content);
        }

        // Cache file not found. Load really resource and write it to cache
        $sections = $this->reallyLoader->load($path);

        $cacheDir = dirname($cacheFile);

        if (!is_dir($cacheDir)) {
            // Create a new cache dir
            if (false === @mkdir($cacheDir, 0777, true)) {
                throw new \RuntimeException(sprintf(
                    'Could not create a cache directory "%s".',
                    $cacheDir
                ));
            }
        }

        if (!is_file($cacheFile)) {
            // Create a new cache file
            if (false === touch($cacheFile)) {
                throw new \RuntimeException(sprintf(
                    'Could not create a cache file "%s".',
                    $cacheFile
                ));
            }
        }

        file_put_contents($cacheFile, serialize($sections));

        return $sections;
    }
}
