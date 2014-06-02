<?php

/**
 * This file is part of the LanguageDetector package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Ideea\LanguageDetector\Visitor;

use Ideea\LanguageDetector\Languages;

/**
 * Interface for control visitors
 */
interface VisitorInterface
{
    /**
     * Visit languages
     *
     * @param string    $string    String for detection
     * @param array     &$codes    Character codes
     * @param Languages $languages Response of languages
     */
    public function visit($string, array &$codes, Languages $languages);
}
