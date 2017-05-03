# sitedyno/phergie-fortune

[Phergie](http://github.com/phergie/phergie-irc-bot-react/) plugin for
displaying a fortune in channel.

:warning: This plugin makes system calls! It should be fine unless
misconfigured. You have been warned. :warning:

This plugin assumes you have installed fortune on your \*nix like system. For
more detailed information on databases see `man fortune`.

[![Build Status](https://secure.travis-ci.org/sitedyno/phergie-fortune.png?branch=master)](http://travis-ci.org/sitedyno/phergie-fortune)
[![codecov](https://codecov.io/gh/sitedyno/phergie-fortune/branch/master/graph/badge.svg)](https://codecov.io/gh/sitedyno/phergie-fortune)

## Install

The recommended method of installation is [through composer](http://getcomposer.org).

`composer require sitedyno/phergie-fortune`

See Phergie documentation for more information on
[installing and enabling plugins](https://github.com/phergie/phergie-irc-bot-react/wiki/Usage#plugins).

## Provided Commands

| Command    | Parameters | Description                          |
|:----------:|------------|--------------------------------------|
| !fortune   | none       | Displays a random fortune in channel |

## Configuration

```php
return [
    'plugins' => [
        // configuration, all is optional
        new \Sitedyno\PhergieFortune\Plugin([
            // The path to the fortune binary. Defaults to '/usr/games/fortune'.
            'binary-path' => '/usr/games/fortune',
            // List of databases to use. Defaults to 'fortunes'. See `man fortune` for possible values.
            'databases' => 'fortune',
            // Use only short fortunes. Defaults to true.
            'short' => true
        ])
    ]
];
```

## Tests

To run the unit test suite:

```
curl -s https://getcomposer.org/installer | php
php composer.phar install
./vendor/bin/phpunit
```

## License

Released under the MIT License. See `LICENSE.md`.
