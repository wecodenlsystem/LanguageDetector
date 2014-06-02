<?php

/**
 * This file is part of the LanguageDetector package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Ideea\LanguageDetector\Dictionary;

/**
 * All dictionary instance should be implement of this interface
 */
interface DictionaryInterface
{
    /**
     * Get languages by word
     *
     * @param string $word
     *
     * @return array
     */
    public function getLanguagesByWord($word);

    /**
     * Add word to dictionary
     *
     * @param string $language
     * @param string $word
     */
    public function addWord($language, $word);

    /**
     * Remove word from directory
     *
     * @param string $language
     * @param string $word
     */
    public function removeWord($language, $word);
}
