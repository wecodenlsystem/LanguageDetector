<?php

/**
 * This file is part of the LanguageDetector package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Ideea\LanguageDetector\Section;

/**
 * Sections storage
 */
class Storage implements \Serializable
{
    /**
     * @var array|Section[]
     */
    private $sections = array();

    /**
     * Construct
     *
     * @param array|Section[] $sections
     */
    public function __construct(array $sections = array())
    {
        $this->sections = $sections;
    }

    /**
     * Get all sections
     *
     * @return array|Section[]
     */
    public function all()
    {
        return $this->sections;
    }

    /**
     * Add section to storage
     *
     * @param Section $section
     */
    public function add(Section $section)
    {
        $this->sections[$section->getKey()] = $section;
    }

    /**
     * Remove section from storage
     *
     * @param Section $section
     */
    public function remove(Section $section)
    {
        unset ($this->sections[$section->getKey()]);
    }

    /**
     * Find section by code
     *
     * @param int $code
     *
     * @return Section|null
     */
    public function findByCode($code)
    {
        foreach ($this->sections as $section) {
            if ($section->getCharCodeFrom() <= $code && $section->getCharCodeTo() >= $code) {
                return $section;
            }
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function serialize()
    {
        return serialize(array(
            $this->sections
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function unserialize($serialized)
    {
        list ($this->sections) = unserialize($serialized);
    }
}
