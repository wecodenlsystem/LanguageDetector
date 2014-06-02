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
 * Section item
 */
class Section implements \Serializable
{
    const TYPE_ALPHABET = 1;
    const TYPE_ABUGIDA = 2;
    const TYPE_SYLLABARY = 3;
    const TYPE_ABJAD = 4;
    const TYPE_SEMISYLLABARY = 5;

    /**
     * @var string
     */
    private $key;

    /**
     * Char codes range
     *
     * @var array
     */
    private $diap;

    /**
     * @var int
     */
    private $type;

    /**
     * @var array
     */
    private $languages;

    /**
     * Construct
     *
     * @param string $key
     * @param array  $diap
     * @param int    $type
     * @param array  $languages
     */
    public function __construct($key, array $diap, $type, array $languages)
    {
        $this->key = $key;
        $this->diap = $diap;
        $this->type = $type;
        $this->languages = $languages;
    }

    /**
     * Get section key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Get char codes range
     *
     * @return array
     */
    public function getDiap()
    {
        return $this->diap;
    }

    /**
     * Get char code from
     *
     * @return string
     */
    public function getCharCodeFrom()
    {
        return $this->diap[0];
    }

    /**
     * Get char code to
     *
     * @return string
     */
    public function getCharCodeTo()
    {
        return $this->diap[1];
    }

    /**
     * Get type
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Is alphabet type
     *
     * @return bool
     */
    public function isAlphabet()
    {
        return $this->type === self::TYPE_ALPHABET;
    }

    /**
     * Is abugida type
     *
     * @return bool
     */
    public function isAbugida()
    {
        return $this->type === self::TYPE_ABUGIDA;
    }

    /**
     * Is syllabary type
     *
     * @return bool
     */
    public function isSyllabary()
    {
        return $this->type === self::TYPE_SYLLABARY;
    }

    /**
     * Is abjad type
     *
     * @return bool
     */
    public function isAbjad()
    {
        return $this->type === self::TYPE_ABJAD;
    }

    /**
     * Is semisyllabary type
     *
     * @return bool
     */
    public function isSemisyllabary()
    {
        return $this->type === self::TYPE_SEMISYLLABARY;
    }

    /**
     * Get languages
     *
     * @return array
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * {@inheritDoc}
     */
    public function serialize()
    {
        return serialize(array(
            $this->key,
            $this->diap,
            $this->type,
            $this->languages
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function unserialize($serialized)
    {
        list (
            $this->key,
            $this->diap,
            $this->type,
            $this->languages
        ) = unserialize($serialized);
    }
}
