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

use Ideea\LanguageDetector\Alphabet\Alphabet;
use Ideea\LanguageDetector\Alphabet\Storage;
use Ideea\Unicode\Unicode;
use Symfony\Component\Yaml\Yaml;

/**
 * Load alphabets from ini file
 */
class YamlLoader implements LoaderInterface
{
    /**
     * {@inheritDoc}
     */
    public function load($path)
    {
        $data = Yaml::parse($path);

        $storage = new Storage();

        if (!$data) {
            return $storage;
        }

        foreach ($data as $language => $alphabetInfo) {
            $chars = Unicode::ordStr($alphabetInfo['chars']);
            $alphabet = new Alphabet($language, $chars);
            $storage->add($alphabet);
        }

        return $storage;
    }
}
