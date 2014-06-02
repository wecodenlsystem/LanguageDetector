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
 * Dictionary loads from files
 */
class PhpFileDictionary implements DictionaryInterface
{
    /**
     * @var string
     */
    private $dictionaryDir;

    /**
     * @var array
     */
    private $words;

    /**
     * Control updated languages for next writes to PHP files
     *
     * @var array
     */
    private $updated = array();

    /**
     * Construct
     *
     * @param string $dictionaryDir
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($dictionaryDir)
    {
        if (!is_dir($dictionaryDir)) {
            throw new \InvalidArgumentException(sprintf(
                'The dictionary dir "%s" not found.',
                $dictionaryDir
            ));
        }

        $this->dictionaryDir = rtrim($dictionaryDir, '/');
    }

    /**
     * {@inheritDoc}
     */
    public function getLanguagesByWord($word)
    {
        $this->loadDictionaries();

        $word = mb_strtolower($word, mb_detect_encoding($word));

        if (isset($this->words[$word])) {
            return $this->words[$word];
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function addWord($language, $word)
    {
        $this->loadDictionaries();

        $word = mb_strtolower($word, mb_detect_encoding($word));

        if (!isset($this->words[$word])) {
            $this->words[$word] = array();
        }

        if (!in_array($language, $this->words[$word])) {
            $this->words[$word][] = $language;
        }

        $this->updated[$language] = true;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function removeWord($language, $word)
    {
        $this->loadDictionaries();

        $word = mb_strtolower($word, mb_detect_encoding($word));

        if (isset($this->words[$word])) {
            if (false !== $index = array_search($language, $this->words[$word])) {
                unset ($this->words[$word][$index]);
            }
        }
    }

    /**
     * Flush updated dictionaries
     */
    public function flush()
    {
        if (!count($this->updated)) {
            // Not updated words
            return;
        }

        foreach ($this->updated as $lang => $true) {
            // Grouping all words by language
            $words = array();

            foreach ($this->words as $word => $wordLanguages) {
                if (in_array($lang, $wordLanguages)) {
                    $words[] = $word;
                }
            }

            $file = $this->dictionaryDir . '/' . $lang . '.php';
            file_put_contents($file, '<?php return ' . var_export($words, 1) . ';');
        }
    }

    /**
     * Load dictionaries from directory
     */
    private function loadDictionaries()
    {
        if (null !== $this->words) {
            // Dictionaries already loaded
            return;
        }

        $this->words = array();

        $dictionaryFiles = glob($this->dictionaryDir . '/*');

        foreach ($dictionaryFiles as $dictionaryFile) {
            $fileName = substr($dictionaryFile, strlen($this->dictionaryDir) + 1);

            preg_match('/^(.+)\./', $fileName, $parts);
            $lang = $parts[1];

            $words = include $dictionaryFile;

            foreach ($words as $word) {
                if (!isset($this->words[$word])) {
                    $this->words[$word] = array();
                }

                if (!in_array($lang, $this->words[$word])) {
                    $this->words[$word][] = $lang;
                }
            }
        }
    }
}
