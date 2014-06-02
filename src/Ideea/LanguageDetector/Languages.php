<?php

/**
 * This file is part of the LanguageDetector package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Ideea\LanguageDetector;

/**
 * Response of language detection
 */
class Languages
{
    const VOTED_HIGH = 1;

    /**
     * All detected languages in text
     *
     * @var array
     */
    private $detectedLanguages = array();

    /**
     * Votes by language
     *
     * @param array
     */
    private $votes = array();

    /**
     * Add detected languages
     *
     * @param string $language
     *
     * @return Languages
     */
    public function addDetectedLanguage($language)
    {
        if (!in_array($language, $this->detectedLanguages)) {
            $this->detectedLanguages[] = $language;
        }

        return $this;
    }

    /**
     * Add detected languages
     *
     * @param array $languages
     *
     * @return Languages
     */
    public function addDetectedLanguages(array $languages)
    {
        foreach ($languages as $language) {
            $this->addDetectedLanguage($language);
        }

        return $this;
    }

    /**
     * Get detected languages
     *
     * @return array
     */
    public function getDetectedLanguages()
    {
        return $this->detectedLanguages;
    }

    /**
     * Has detected language
     *
     * @param string $language
     *
     * @return bool
     */
    public function hasDetectedLanguage($language)
    {
        return in_array($language, $this->detectedLanguages);
    }

    /**
     * Get vote languages
     *
     * @param int $mode
     *
     * @return array
     */
    public function getVoteLanguages($mode = null)
    {
        arsort($this->votes, SORT_NUMERIC);

        if (self::VOTED_HIGH === $mode) {
            if (!count($this->votes)) {
                return $this->votes;
            }

            $languages = array();

            // Get first item
            list ($language, $highVote) = each($this->votes);
            $languages[] = $language;

            while ($item = each($this->votes)) {
                list ($language, $votes) = $item;
                if ($highVote === $votes) {
                    $languages[] = $language;
                } else {
                    break;
                }
            }

            return $languages;

        }

        return $this->votes;
    }

    /**
     * Vote language
     *
     * @param string $language
     * @param int    $index
     *
     * @return Languages
     */
    public function vote($language, $index = 1)
    {
        if (in_array($language, $this->detectedLanguages)) {
            if (!isset($this->votes[$language])) {
                $this->votes[$language] = 0;
            }

            $this->votes[$language] += $index;
        }

        return $this;
    }

    /**
     * Devote language
     *
     * @param string $language
     * @param int    $index
     *
     * @return Languages
     */
    public function devote($language, $index = 1)
    {
        if (in_array($language, $this->detectedLanguages)) {
            if (isset($this->votes[$language])) {
                $this->votes[$language] -= $index;
            }
        }

        return $this;
    }

    /**
     * Get primary language
     *
     * @param array $priorityLanguages
     *
     * @return string
     */
    public function getPrimaryLanguage(array $priorityLanguages = array())
    {
        $votedLanguages = $this->getVoteLanguages(self::VOTED_HIGH);

        if (!count($votedLanguages)) {
            $votedLanguages = $this->getVoteLanguages();

            if (!count($votedLanguages)) {
                return null;
            }
        }

        if (!count($priorityLanguages)) {
            return array_shift($votedLanguages);
        }

        if (count($priorityLanguages) == 1) {
            return $priorityLanguages[0];
        }

        foreach ($priorityLanguages as $priorityLanguage) {
            if (in_array($priorityLanguage, $votedLanguages)) {
                return $priorityLanguage;
            }
        }

        return array_shift($priorityLanguages);
    }
}
