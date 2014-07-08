<?php

/**
 * This file is part of the LanguageDetector package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Ideea\LanguageDetector\Alphabet;

/**
 * Alphabet item
 */
class Alphabet implements \Serializable
{
    /**
     * Language code in ISO-639-1
     *
     * @var string
     */
    private $language;

    /**
     * Alphabet characters
     *
     * @var array
     */
    private $charCodes;

    /**
     * Multiple char codes
     *
     * @var array
     */
    private $multipleCharCodes;

    /**
     * Common char codes
     *
     * @var array
     */
    private $commonCharCodes;

    /**
     * Construct
     *
     * @param string $language
     * @param array  $charCodes
     * @param array  $commonCharCodes
     * @param array  $multipleCharCodes
     */
    public function __construct($language, array $charCodes, array $commonCharCodes, array $multipleCharCodes)
    {
        $this->language = $language;
        $this->charCodes = $charCodes;
        $this->commonCharCodes = $commonCharCodes;
        $this->multipleCharCodes = $multipleCharCodes;
    }

    /**
     * Get language code
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Get char codes
     *
     * @return array
     */
    public function getCharCodes()
    {
        return $this->charCodes;
    }

    /**
     * Get multiple char codes
     *
     * @return array
     */
    public function getMultipleCharCodes()
    {
        return $this->multipleCharCodes;
    }

    /**
     * Get common char codes
     *
     * @return array
     */
    public function getCommonCharCodes()
    {
        return $this->commonCharCodes;
    }

    /**
     * Is alphabet has char code
     *
     * @param int $charCode
     *
     * @return bool
     */
    public function hasChar($charCode)
    {
        return in_array($charCode, $this->charCodes);
    }

    /**
     * Is alphabet has common char code
     *
     * @param int $charCode
     *
     * @return bool
     */
    public function hasCommonChar($charCode)
    {
        return in_array($charCode, $this->commonCharCodes);
    }

    /**
     * {@inheritDoc}
     */
    public function serialize()
    {
        return serialize(array(
            $this->language,
            $this->charCodes,
            $this->multipleCharCodes,
            $this->commonCharCodes
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function unserialize($serialized)
    {
        list (
            $this->language,
            $this->charCodes,
            $this->multipleCharCodes,
            $this->commonCharCodes
        ) = unserialize($serialized);
    }
}
