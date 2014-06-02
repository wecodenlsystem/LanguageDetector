Language detector
=================

Detection language from string

Installation
------------

Add LanguageDetector in your composer.json:

```js
{
    "require": {
        "ideea/language-detector": "~1.0"
    }
}
```

Now tell composer to download the library by running the command:

```bash
$ php composer.phar update "ideea/language-detector"
```

Work with language detector
---------------------------

```php
use Ideea\LanguageDetector\LanguageDetector;
use Ideea\LanguageDetector\Languages;

$detector = LanguageDetector::createDefault();
$text = 'your text';

$languages = $detector->detect($text);

// Get all votes languages
$votedLanguages = $languages->getVoteLanguages();
// Get all high votes languages
$highVotesLanguages = $languages->getVoteLanguages(Languages::VOTED_HIGH);
// Get the primary language of text
$language = $languages->getPrimaryLanguage();
```

> **Attention:** more text - the better definition

Available languages for detection
---------------------------------

* BG
* DE
* EN
* ES
* FI
* FR
* IT
* RU
* SV
* UK

Detection system
----------------

The detection system work with visitor pattern. Each visitor can vote or devote by language.
Available visitors:

* **SectionVisitor**
* **AlphabetVisitor**
* **DictionaryVisitor**

You can create a custom visitor and add to detector.

```php
use Ideea\LanguageDetector\Visitor\VisitorInterface;

class CustomVisitor implements VisitorInterface
{
    /**
     * {@inheritDoc}
     */
    public function visit($string, array &$codes, \Ideea\LanguageDetector\Languages $languages)
    {
        // Your code here
    }
}

$customVisitor = new CustomVisitor();

$detector->addVisitor($customVisitor);
```

Each visitor can add, delete language from detection, vote and devote languages.

Dictionaries
------------

This component has a `*.dict` file dictionaries for available languages. Your can write this dictionaries
to `Redis` storage and check languages with check work in dictionaries.

You can use `RedisDictionary` and populate this dictionary from dictionaries.

```php
use Ideea\LanguageDetector\Util\Util;

// Create new redis instance
$redis = new \Redis();
$redis->connect($host, $port, $timeout);

Util::populateRedisDictionary($redis, __DIR__ . '/data/dictionary', null, function ($file, $count){
    print "File: " . $file . "\n";
    print "Count: " . $count . "\n";
    print "Memory: " . round(memory_get_usage() / (1024 * 1024), 2) . " Mb\n\n";
});
```

> **Note:** If you want use the prefix for redis dictionary, then use a redis system prefix
> `Redis::setOption(Redis::OPT_PREFIX, 'your_prefix')`.

> **Note:** All dictionaries generated from **ASPELL** dictionaries.

Generate dictionary
-------------------

Your can create a custom dictionary. This package used dictionaries from **ASPELL** utility
[http://ftp.gnu.org/gnu/aspell/dict/](http://ftp.gnu.org/gnu/aspell/dict/)

Example of generate new dictionary from **aspell**:

**Step #1:** Load dictionary from source if dictionary not found in system

```bash
$ wget http://source-to-aspell-dicts.com/path/to/dict.tar
# Unpack package
$ cd /path/to/unpacked/dict
$ ./configure
$ make
$ make install
```

**Step #2:** Get word lists from aspell dictionary:

```bash
$ aspell -d {LANG} dump master | aspell -l {LANG} expand > /data/dictionary/{LANG}.dict
```

Where: **{LANG}** - language code in ISO-639-2

And the next step you can populate redis dictionary only for this dict.

```php
Util::populateRedisDictionary($redis, '/data/dictionary/{LANG}.dict', '{LANG}');
```

Caching loaders (Alphabet and Sections)
---------------------------------------

In production environment you must use caching system, because parse `*.yml` file - high load operation for
simple function (detection language).

Alphabet and section loader have a `FileCacheLoader` for caching data with use serialize/unserialize functions.

Usage example:

```php
use Ideea\LanguageDetector\Section\Loader\YamlLoader as SectionLoader;
use Ideea\LanguageDetector\Section\Loader\FileCacheLoader as SectionCacheLoader;
use Ideea\LanguageDetector\Alphabet\Loader\YamlLoader as AlphabetLoader;
use Ideea\LanguageDetector\Alphabet\Loader\FileCacheLoader as AlphabetCacheLoader;

// Create a base loaders
$sectionLoader = new SectionLoader();
$alphabetLoader = new AlphabetLoader();

// Create wrapping for loaders
$sectionLoader = new SectionCacheLoader($sectionLoader, '/path/to/cache/dir');
$alphabetLoader = new AlphabetCacheLoader($alphabetLoader, '/path/to/cache/dir');
```

License
-------

This library is under the MIT license. See the complete license in library

```
LICENSE
```

Reporting an issue or a feature request
---------------------------------------

Issues and feature requests are tracked in the [GitHub issue tracker](https://github.com/ZhukV/LanguageDetector/issues)

Tanks for the help
------------------

* [Unicode Table](https://github.com/unicode-table): provided all the section tables

TODO list
---------

* Add languages
* Add PHPUnit tests