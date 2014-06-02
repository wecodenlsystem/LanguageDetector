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
 * All section loaders should be implement of this interface
 */
interface LoaderInterface
{
    /**
     * Load sections
     *
     * @param string $path
     *
     * @return \Ideea\LanguageDetector\Section\Storage
     */
    public function load($path);
}
