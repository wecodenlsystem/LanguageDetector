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
     * Construct
     *
     * @param string $language  Language identifier (ISO-639-1)
     * @param array  $charCodes Alphabet char codes
     */
    public function __construct($language, array $charCodes)
    {
        $this->language = $language;
        $this->charCodes = $charCodes;
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
     * {@inheritDoc}
     */
    public function serialize()
    {
        return serialize(array(
            $this->language,
            $this->charCodes
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function unserialize($serialized)
    {
        list ($this->language, $this->charCodes) = unserialize($serialized);
    }
}
