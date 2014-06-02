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

use Ideea\LanguageDetector\Section\Section;
use Symfony\Component\Yaml\Yaml;
use Ideea\LanguageDetector\Section\Storage;

/**
 * Load sections from *.yml files
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

        $types = array(
            'alphabet' => Section::TYPE_ALPHABET,
            'abugida' => Section::TYPE_ABUGIDA,
            'syllabary' => Section::TYPE_SYLLABARY,
            'abjad' => Section::TYPE_ABJAD,
            'semisyllabary' => Section::TYPE_SEMISYLLABARY
        );

        foreach ($data as $sectionKey => $sectionInfo) {
            $languages = $sectionInfo['languages'];
            $type = $sectionInfo['type'];
            $diap = $sectionInfo['diap'];

            if (null === $languages) {
                $languages = array();
            }

            if ($type) {
                if (!isset($types[$type])) {
                    throw new \RuntimeException(sprintf(
                        'Undefined type "%s".',
                        $type
                    ));
                }

                $type = $types[$type];
            }

            $section = new Section($sectionKey, $diap, $type, $languages);

            $storage->add($section);
        }

        return $storage;
    }
}
