<?php
/**
 * Phergie plugin for displayng a random fortune in channel (https://github.com/sitedyno/phergie-fortune)
 *
 * @link https://github.com/sitedyno/phergie-fortune for the canonical source repository
 * @copyright Copyright (c) 2017 Heath Nail (https://github.com/sitedyno)
 * @license https://opensource.org/licenses/MIT MIT
 * @package Sitedyno\PhergieFortune
 */

namespace Sitedyno\PhergieFortune\Tests;

use DomainException;
use Phake;
use Phergie\Irc\Bot\React\EventQueueInterface as Queue;
use Phergie\Irc\Plugin\React\Command\CommandEvent as Event;
use Sitedyno\PhergieFortune\Plugin;

/**
 * Tests for the Plugin class.
 *
 * @category Sitedyno
 * @package Sitedyno\PhergieFortune
 */
class PluginTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Configurations for testGetFortuneCommand()
     */
    public function getFortuneCommandConfigurations()
    {
        yield [
            ['short' => false],
            '/usr/games/fortune fortunes'
        ];

        yield [
            ['databases' => '10% cookie fortunes'],
            '/usr/games/fortune -s 10% cookie fortunes'
        ];
    }

    /**
     * Data provider for testInvalidConfigurations()
     */
    public function invalidConfigurations()
    {
        yield [
            ['binary-path' => false],
            Plugin::INVALID_BINARY_PATH
        ];

        yield [
            ['databases' => false],
            Plugin::INVALID_DATABASES
        ];

        yield [
            ['short' => 'not boolean'],
            Plugin::INVALID_SHORT
        ];
    }

    /**
     * Tests getFortuneCommand()
     *
     * @param array $config
     * @param string $expected
     * @dataProvider getFortuneCommandConfigurations
     */
    public function testGetFortuneCommand($config, $expected)
    {
        $plugin = new Plugin($config);

        $result = $plugin->getFortuneCommand();

        $this->assertSame($expected, $result);
    }

    /**
     * Tests that getSubscribedEvents() returns an array.
     */
    public function testGetSubscribedEvents()
    {
        $plugin = new Plugin;
        $this->assertInternalType('array', $plugin->getSubscribedEvents());
    }

    /**
     * Tests that handleFortune() will log an error with non-zero exit code.
     */
    public function testHandleFortuneNonZeroExitCode()
    {
        $event = Phake::mock('\Phergie\Irc\Plugin\React\Command\CommandEvent');
        $queue = Phake::mock('\Phergie\Irc\Bot\React\EventQueueInterface');
        $logger = Phake::mock('\Monolog\Logger');
        $loop = \React\EventLoop\Factory::create();

        $plugin = new Plugin(['binary-path' => 'not_existing_binary']);
        $plugin->setLogger($logger);
        $plugin->setLoop($loop);


        $plugin->handleFortune($event, $queue);

        $loop->run();

        Phake::verify($logger)->error("not_existing_binary -s fortunes exited with exit code: 127");
    }

    /**
     * Tests handleFortune() queues a message on success.
     */
    public function testHandleFortuneQueuesMessageOnSuccess()
    {
        $event = Phake::mock('\Phergie\Irc\Plugin\React\Command\CommandEvent');
        $queue = Phake::mock('\Phergie\Irc\Bot\React\EventQueueInterface');
        $loop = \React\EventLoop\Factory::create();
        $fortune = "You will find a rewarding development job soon!";

        Phake::when($event)->getSource()->thenReturn('#sitedyno');
        Phake::when($event)->getNick()->thenReturn('sitedyno');

        $plugin = new Plugin([
            'binary-path' => 'echo',
            'databases' => $fortune,
            'short' => false
        ]);
        $plugin->setLoop($loop);

        $plugin->handleFortune($event, $queue);

        $loop->run();

        Phake::verify($queue)->ircPrivmsg(
            '#sitedyno',
            "sitedyno: $fortune "
        );
    }

    /**
     * Tests that an exception is thrown for invalid configurations.
     *
     * @param array $config
     * @param int $error
     * @dataProvider invalidConfigurations
     */
    public function testInvalidConfigurations(array $config, $error)
    {
        try {
            $plugin = new Plugin($config);
        } catch (DomainException $e) {
            $this->assertSame($error, $e->getCode());
        }
    }

    /**
     * Tests that SetBinaryPath() accepts a string.
     */
    public function testSetBinaryPathAcceptsString()
    {
        $plugin = new Plugin;
        $path = '/some/path';

        $plugin->setBinaryPath($path);
        $result = $plugin->getBinaryPath();

        $this->assertSame($path, $result);
    }

    /**
     * Tests that setDatabases() accepts a string.
     */
    public function testSetDatabasesAcceptsString()
    {
        $plugin = new Plugin;
        $databases = 'startrek';

        $plugin->setDatabases($databases);
        $result = $plugin->getDatabases();

        $this->assertSame($databases, $result);
    }

    /**
     * Tests that setShort() accepts false.
     */
    public function testSetShortAcceptsFalse()
    {
        $plugin = new Plugin;
        $expected = false;

        $plugin->setShort($expected);
        $result = $plugin->getShort();

        $this->assertSame($expected, $result);
    }

}
