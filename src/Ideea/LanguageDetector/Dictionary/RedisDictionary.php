<?php

/**
 * This file is part of the LanguageDetector package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Ideea\LanguageDetector\Dictionary;

/**
 * Dictionary loads from Redis server
 */
class RedisDictionary implements DictionaryInterface
{
    /**
     * @var \Redis
     */
    private $redis;

    /**
     * Construct
     *
     * @param \Redis $redis
     */
    public function __construct(\Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * {@inheritDoc}
     */
    public function getLanguagesByWord($word)
    {
        $word = mb_strtolower($word, mb_detect_encoding($word));

        if ($this->redis->exists($word)) {
            return unserialize($this->redis->get($word));
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function addWord($language, $word)
    {
        $word = mb_strtolower($word, mb_detect_encoding($word));

        if ($this->redis->exists($word)) {
            $languages = unserialize($this->redis->get($word));

            if (!in_array($language, $languages)) {
                $languages[] = $language;
            }
        } else {
            $languages = array($language);
        }

        $this->redis->set($word, serialize($languages));

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function removeWord($language, $word)
    {
        $word = mb_strtolower($word, mb_detect_encoding($word));

        if ($this->redis->exists($word)) {
            $languages = unserialize($this->redis->get($word));

            if (false !== $index = array_search($language, $languages)) {
                unset ($languages[$index]);

                if (!count($languages)) {
                    $this->redis->del($word);
                } else {
                    $this->redis->set($word, serialize($languages));
                }
            }
        }

        return $this;
    }
}
