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

use Ideea\LanguageDetector\Dictionary\DictionaryInterface;
use Ideea\LanguageDetector\Visitor\AlphabetVisitor;
use Ideea\LanguageDetector\Visitor\DictionaryVisitor;
use Ideea\LanguageDetector\Visitor\SectionVisitor;
use Ideea\LanguageDetector\Visitor\VisitorInterface;
use Ideea\Unicode\Unicode;
use Ideea\LanguageDetector\Alphabet\Loader\YamlLoader as AlphabetLoader;
use Ideea\LanguageDetector\Section\Loader\YamlLoader as SectionLoader;

/**
 * Detection language
 */
class LanguageDetector
{
    /**
     * @var array
     */
    private $visitors;

    /**
     * @var array|VisitorInterface[]
     */
    private $sortedVisitors;

    /**
     * Add detection visitor
     *
     * @param VisitorInterface $visitor
     * @param int              $priority
     *
     * @return LanguageDetector
     */
    public function addVisitor(VisitorInterface $visitor, $priority = 0)
    {
        $this->sortedVisitors = null;

        $this->visitors[spl_object_hash($visitor)] = array(
            'priority' => $priority,
            'visitor' => $visitor
        );

        return $this;
    }

    /**
     * Get all visitors for detect language
     *
     * @return array|VisitorInterface[]
     */
    public function getVisitors()
    {
        $this->sortVisitors();

        return $this->sortedVisitors;
    }

    /**
     * Detect languages
     *
     * @param string $string
     *
     * @return Languages
     */
    public function detect($string)
    {
        // Remove special chars
        $codes = Unicode::ordStr($string);

        $languages = new Languages();

        foreach ($this->getVisitors() as $visitor) {
            $visitor->visit($string, $codes, $languages);
        }

        return $languages;
    }

    /**
     * Sort visitors
     */
    private function sortVisitors()
    {
        if (null !== $this->sortedVisitors) {
            // Visitors already sorted
            return;
        }

        uasort($this->visitors, function ($a, $b) {
            if ($a['priority'] == $b['priority']) {
                return 0;
            }

            return $a['priority'] > $b['priority'] ? 1 : 0;
        });

        $this->sortedVisitors = array();

        foreach ($this->visitors as $visitorItem) {
            $this->sortedVisitors[] = $visitorItem['visitor'];
        }
    }

    /**
     * Create default detector
     *
     * @param DictionaryInterface $dictionary
     *
     * @return LanguageDetector
     */
    public static function createDefault(DictionaryInterface $dictionary = null)
    {
        /** @var LanguageDetector $detector */
        $detector = new static();

        $dataDirectory = realpath(__DIR__ . '/../../../data');

        $alphabetLoader = new AlphabetLoader();
        $sectionLoader = new SectionLoader();

        $sections = $sectionLoader->load($dataDirectory . '/sections.yml');
        $alphabets = $alphabetLoader->load($dataDirectory . '/alphabets.yml');

        $detector
            ->addVisitor(new SectionVisitor($sections), -1024)
            ->addVisitor(new AlphabetVisitor($alphabets), -512);

        if ($dictionary) {
            $detector->addVisitor(new DictionaryVisitor($dictionary), -256);
        }

        return $detector;
    }
}
