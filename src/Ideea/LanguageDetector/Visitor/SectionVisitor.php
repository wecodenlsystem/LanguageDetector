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
use Ideea\LanguageDetector\Section\Storage as SectionStorage;

/**
 * Detect languages by sections
 */
class SectionVisitor implements VisitorInterface
{
    /**
     * @var SectionStorage
     */
    private $sections;

    /**
     * Construct
     *
     * @param SectionStorage $sectionStorage
     */
    public function __construct(SectionStorage $sectionStorage)
    {
        $this->sections = $sectionStorage;
    }

    /**
     * {@inheritDoc}
     */
    public function visit($string, array &$codes, Languages $languages)
    {
        foreach ($codes as $key => $code) {
            if (
                ($code >= 32 && $code <= 47) ||
                ($code >= 58 && $code <= 64) ||
                ($code >= 91 && $code <= 96) ||
                ($code >= 123 && $code <= 127)
            ) {
                // This is a special char. Not detected.
                unset ($codes[$key]);
                continue;
            }

            $section = $this->sections->findByCode($code);

            if ($section === null) {
                // Not found section for this char code
                continue;
            }

            if (count($section->getLanguages()) === 0) {
                // Not found languages for this char code.
                // This chan can is a special char (music, arrows, etc...)
                unset ($codes[$key]);
            }

            $languages->addDetectedLanguages($section->getLanguages());
        }
    }
}
