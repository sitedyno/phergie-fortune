<?php
/**
 * Phergie plugin for displaying a random fortune in channel (https://github.com/sitedyno/phergie-fortune)
 *
 * @link https://github.com/sitedyno/phergie-fortune for the canonical source repository
 * @copyright Copyright (c) 2017 Heath Nail (https://github.com/sitedyno)
 * @license https://opensource.org/licenses/MIT MIT
 * @package Sitedyno\PhergieFortune
 */

namespace Sitedyno\PhergieFortune;

use DomainException;
use Phergie\Irc\Bot\React\AbstractPlugin;
use Phergie\Irc\Bot\React\EventQueueInterface as Queue;
use Phergie\Irc\Plugin\React\Command\CommandEvent as Event;
use React\ChildProcess\Process;

/**
 * Plugin class.
 *
 * @category Sitedyno
 * @package Sitedyno\PhergieFortune
 */
class Plugin extends AbstractPlugin
{
    /**
     * Path to the fortune binary
     *
     * @var string
     */
    protected $binaryPath = '/usr/games/fortune';

    /**
     * List of fortune databases to use.
     *
     * @var string
     */
    protected $databases = 'fortunes';

    /**
     * Invalid binary path.
     *
     * @var int
     */
    const INVALID_BINARY_PATH = 1;

    /**
     * Invalid databases value.
     *
     * @var int
     */
    const INVALID_DATABASES = 2;

    /**
     * Invalid short value.
     *
     * @var int
     */
    const INVALID_SHORT = 3;

    /**
     * True to use short fortunes (-s). False to use any length fortunes.
     *
     * @var bool
     */
    protected $short = true;

    /**
     * Accepts plugin configuration.
     *
     * Supported keys:
     *
     * binary-path - The path to the fortune binary. Defaults to '/usr/games/fortune'.
     *
     * databases - List of databases to use. Defaults to 'fortunes'. See `man fortune` for possible values.
     *
     * short - Use only short fortunes. Defaults to true.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        if (isset($config['binary-path'])) {
            $this->setBinaryPath($config['binary-path']);
        }
        if (isset($config['databases'])) {
            $this->setDatabases($config['databases']);
        }
        if (isset($config['short'])) {
            $this->setShort($config['short']);
        }
    }

    /**
     * Gets the path to the fortune binary.
     *
     * @return string
     */
    public function getBinaryPath()
    {
        return $this->binaryPath;
    }

    /**
     * Gets the databases to use for the fortune command.
     *
     * @return string
     */
    public function getDatabases()
    {
        return $this->databases;
    }

    /**
     * Gets the fortune command.
     *
     * @return string
     */
    public function getFortuneCommand()
    {
        if ($this->short) {
            $short = ' -s';
        } else {
            $short = null;
        }
        $cmd = $this->binaryPath . $short . " " . $this->databases;
        return $cmd;
    }

    /**
     * Gets the value for short.
     *
     * @return bool
     */
    public function getShort()
    {
        return $this->short;
    }

    /**
     * Maps events to functions.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            'command.fortune' => 'handleFortune',
        ];
    }

    /**
     * Display a random fortune in channel.
     *
     * @param \Phergie\Irc\Plugin\React\Command\CommandEvent $event
     * @param \Phergie\Irc\Bot\React\EventQueueInterface $queue
     */
    public function handleFortune(Event $event, Queue $queue)
    {
        $fortune = new Process($this->getFortuneCommand());
        $fortune->on('exit', function($exitCode, $termSignal) {
            if (0 !== $exitCode) {
                $cmd = $this->getFortuneCommand();
                $this->logger->error("$cmd exited with exit code: $exitCode");
            }
        });
        $fortune->start($this->getLoop());
        $fortune->stdout->on('data', function ($chunk) use ($event, $queue) {
            $chunk = str_replace("\n", " ", $chunk);
            $queue->ircPrivmsg(
                $event->getSource(),
                $event->getNick() . ": " . $chunk
            );
        });
    }

    /**
     * Sets the path to the fortune binary.
     *
     * @return void
     */
    public function setBinaryPath($path)
    {
        if (!is_string($path)) {
            throw new DomainException(
                'binary-path must be a string',
                Plugin::INVALID_BINARY_PATH
            );
        }
        $this->binaryPath = $path;
    }

    /**
     * Sets the databases to use for the fortune command.
     *
     * @return void
     */
    public function setDatabases($databases)
    {
        if (!is_string($databases)) {
            throw new DomainException(
                'databases must be a string',
                Plugin::INVALID_DATABASES
            );
        }
        $this->databases = $databases;
    }

    /**
     * Sets the short value.
     *
     * @return void
     */
    public function setShort($short)
    {
        if (!is_bool($short)) {
            throw new DomainException(
                'short must be true or false',
                Plugin::INVALID_SHORT
            );
        }
        $this->short = $short;
    }
}
