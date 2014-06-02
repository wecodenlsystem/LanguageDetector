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
use Ideea\LanguageDetector\Alphabet\Storage as AlphabetStorage;

/**
 * Alphabet visitor
 */
class AlphabetVisitor implements VisitorInterface
{
    /**
     * @var AlphabetStorage
     */
    private $alphabets;

    /**
     * Construct
     *
     * @param AlphabetStorage $alphabets
     */
    public function __construct(AlphabetStorage $alphabets)
    {
        $this->alphabets = $alphabets;
    }

    /**
     * {@inheritDoc}
     */
    public function visit($string, array &$codes, Languages $languages)
    {
        if (!count($languages->getDetectedLanguages())) {
            // Not found detected languages.
            return;
        }

        /** @var \Ideea\LanguageDetector\Alphabet\Alphabet[] $checkAlphabets */
        $checkAlphabets = array();

        foreach ($languages->getDetectedLanguages() as $detectedLanguage) {
            $alphabet = $this->alphabets->get($detectedLanguage);

            if (null === $alphabet) {
                // Not found alphabet for language
                continue;
            }

            $checkAlphabets[$alphabet->getLanguage()] = $alphabet;
        }

        foreach ($codes as $code) {
            foreach ($checkAlphabets as $alphabet) {
                if ($alphabet->hasChar($code)) {
                    $languages->vote($alphabet->getLanguage());
                }
            }
        }

        // Devote languages if language chars not used
        // Can remove this functional?
        $votedLanguages = $languages->getVoteLanguages();

        if (count($votedLanguages) > 1) {
            foreach ($votedLanguages as $votedLanguage => $votes) {
                $votedLanguageAlphabet = $this->alphabets->get($votedLanguage);

                if (null === $votedLanguage) {
                    // Not alphabet exists for voted language
                    continue;
                }

                $languageAlphabetChars = $votedLanguageAlphabet->getCharCodes();
                $languageUsedChars = array_intersect(array_unique($codes), $languageAlphabetChars);

                $countUnusedChars = count($languageAlphabetChars) - count($languageUsedChars);
                if ($countUnusedChars > 2) {
                    $languages->devote($votedLanguage, $countUnusedChars * 0.02);
                }
            }
        }
    }
}
