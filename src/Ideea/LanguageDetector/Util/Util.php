<?php

/**
 * This file is part of the LanguageDetector package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Ideea\LanguageDetector\Util;

use Ideea\LanguageDetector\Dictionary\RedisDictionary;
use Ideea\Unicode\Unicode;
use Symfony\Component\Yaml\Yaml;

/**
 * Utilities for work with sections and alphabet
 */
class Util
{
    /**
     * Fix section languages by alphabet
     *
     * @param string $alphabetsFile
     * @param string $sectionsFile
     *
     * @throws \InvalidArgumentException
     */
    public static function fixSectionLanguagesByAlphabet($alphabetsFile, $sectionsFile)
    {
        if (!is_file($alphabetsFile)) {
            throw new \InvalidArgumentException(sprintf(
                'File "%s" not found.',
                $alphabetsFile
            ));
        }

        if (!is_file($sectionsFile)) {
            throw new \InvalidArgumentException(sprintf(
                'File "%s" not found.',
                $sectionsFile
            ));
        }

        $sections = Yaml::parse($sectionsFile);
        $alphabets = Yaml::parse($alphabetsFile);

        if (!$sections || !$alphabets) {
            return;
        }

        foreach ($alphabets as $language => $alphabetInfo) {
            $alphabetChars = Unicode::ordStr($alphabetInfo['chars']);

            $availableSections = array();

            foreach ($alphabetChars as $char) {
                foreach ($sections as $section => $sectionInfo) {
                    list ($diapMin, $diapMax) = $sectionInfo['diap'];

                    if ($char >= $diapMin && $char <= $diapMax) {
                        $availableSections[] = $section;
                    }
                }
            }

            if (count($availableSections)) {
                foreach ($availableSections as $availableSection) {
                    if (!is_array($sections[$availableSection]['languages'])) {
                        $sections[$availableSection]['languages'] = array();
                    }

                    if (!in_array($language, $sections[$availableSection]['languages'])) {
                        $sections[$availableSection]['languages'][] = $language;
                    }
                }
            }
        }

        file_put_contents($sectionsFile, Yaml::dump($sections));
    }

    /**
     * Remove all section languages
     *
     * @param string $sectionsFile
     *
     * @throws \InvalidArgumentException
     */
    public static function removeSectionLanguages($sectionsFile)
    {
        if (!is_file($sectionsFile)) {
            throw new \InvalidArgumentException(sprintf(
                'Section file "%s" not found.',
                $sectionsFile
            ));
        }

        $sections = Yaml::parse($sectionsFile);

        foreach ($sections as $section => $sectionInfo) {
            $sections[$section]['languages'] = null;
        }

        file_put_contents($sectionsFile, Yaml::dump($sections));
    }

    /**
     * Populate redis dictionary storage with use PHP file dictionary
     *
     * @param \Redis   $redis
     * @param string   $dictPath
     * @param array    $languages
     * @param callable $fileCallback
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public static function populateRedisDictionary(
        \Redis $redis,
        $dictPath,
        array $languages = null,
        $fileCallback = null
    ) {
        $redisDictionary = new RedisDictionary($redis);

        $dictPath = rtrim($dictPath, '/');

        if (!is_dir($dictPath)) {
            throw new \InvalidArgumentException(sprintf(
                'The directory "%s" not found.',
                $dictPath
            ));
        }

        if ($fileCallback && !is_callable($fileCallback)) {
            throw new \InvalidArgumentException(sprintf(
                'The callback must be a callable, %s given.',
                is_object($fileCallback) ? get_class($fileCallback) : gettype($fileCallback)
            ));
        }

        $files = glob($dictPath . '/*');

        foreach ($files as $file) {
            $fileName = substr($file, strlen($dictPath) + 1);

            if (!preg_match('/^(.+)\.(.+)$/', $fileName, $tmp)) {
                throw new \RuntimeException(sprintf(
                    'Could not parse language from file name "%s".',
                    $fileName
                ));
            }

            $lang = $tmp[1];
            $ext = $tmp[2];

            if ($languages !== null && !in_array($lang, $languages)) {
                continue;
            }

            if ('php' == $ext) {
                // PHP File dictionary
                $words = include $file;
            } elseif (in_array($ext, array('dict', 'txt'))) {
                // Base dictionary: one word (or more then 1) on one line
                $words = file($file);
            } else {
                throw new \RuntimeException(sprintf(
                    'Undefined file with ext "%s". Supported "php" and "dict".',
                    $ext
                ));
            }

            $words = array_map('trim', $words);

            foreach ($words as $wordLine) {
                $wordsLine = explode(' ', $wordLine);

                $wordsLine = array_map('trim', $wordsLine);

                foreach ($wordsLine as $word) {
                    if (!$word) {
                        continue;
                    }

                    $redisDictionary->addWord($lang, $word);
                }
            }

            if ($fileCallback) {
                call_user_func($fileCallback, $file, count($words));
            }

            unset ($words);
            gc_collect_cycles();
        }
    }
}
