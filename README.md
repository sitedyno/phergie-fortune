# sitedyno/phergie-fortune

[Phergie](http://github.com/phergie/phergie-irc-bot-react/) plugin for Display a fortune in channel.

[![Build Status](https://secure.travis-ci.org/sitedyno/phergie-fortune.png?branch=master)](http://travis-ci.org/sitedyno/phergie-fortune)

## Install

The recommended method of installation is [through composer](http://getcomposer.org).

`php composer.phar require sitedyno/phergie-fortune`

See Phergie documentation for more information on
[installing and enabling plugins](https://github.com/phergie/phergie-irc-bot-react/wiki/Usage#plugins).

## Provided Commands

| Command    | Parameters        | Description           |
|:----------:|-------------------|-----------------------|
| {commmand} | [param1] [param2] | {description}         |
## Configuration

```php
return [
    'plugins' => [
        // configuration
        new \Sitedyno\PhergieFortune\Plugin([



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

Released under the BSD License. See `LICENSE`.
