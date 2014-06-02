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

use Ideea\LanguageDetector\Dictionary\DictionaryInterface;
use Ideea\LanguageDetector\Languages;

/**
 * Dictionary visitor
 */
class DictionaryVisitor implements VisitorInterface
{
    /**
     * @var DictionaryInterface
     */
    private $dictionary;

    /**
     * Construct
     *
     * @param DictionaryInterface $dictionary
     */
    public function __construct(DictionaryInterface $dictionary)
    {
        $this->dictionary = $dictionary;
    }

    /**
     * {@inheritDoc}
     */
    public function visit($string, array &$codes, Languages $languages)
    {
        $words = preg_split('/(\s|\n)/', $string);

        foreach ($words as $word) {
            $word = trim($word, '.,?!-');
            $wordLanguages = $this->dictionary->getLanguagesByWord($word);

            if (null !== $wordLanguages) {
                foreach ($wordLanguages as $wordLanguage) {
                    $languages->vote($wordLanguage, 0.5);
                }
            }
        }
    }
}
