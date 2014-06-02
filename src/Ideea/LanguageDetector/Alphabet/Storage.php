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
 * Alphabet storage
 */
class Storage implements \Serializable
{
    /**
     * @var array|Alphabet[]
     */
    private $alphabets;

    /**
     * Construct
     *
     * @param array|Alphabet[] $alphabets
     */
    public function __construct(array $alphabets = array())
    {
        $this->alphabets = $alphabets;
    }

    /**
     * Get all alphabets
     *
     * @return array|Alphabet[]
     */
    public function all()
    {
        return $this->alphabets;
    }

    /**
     * Add alphabet
     *
     * @param Alphabet $alphabet
     *
     * @return Storage
     */
    public function add(Alphabet $alphabet)
    {
        $this->alphabets[$alphabet->getLanguage()] = $alphabet;

        return $this;
    }

    /**
     * Get alphabet for language
     *
     * @param string $language
     *
     * @return Alphabet|null
     */
    public function get($language)
    {
        if (!isset($this->alphabets[$language])) {
            // Not found alphabet for language
            return null;
        }

        return $this->alphabets[$language];
    }

    /**
     * Remove alphabet from storage
     *
     * @param Alphabet $alphabet
     *
     * @return Storage
     */
    public function remove(Alphabet $alphabet)
    {
        unset ($this->alphabets[$alphabet->getLanguage()]);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function serialize()
    {
        return serialize(array(
            $this->alphabets
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function unserialize($serialized)
    {
        list ($this->alphabets) = unserialize($serialized);
    }
}
