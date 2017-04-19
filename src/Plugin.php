<?php
/**
 * Phergie plugin for Display a fortune in channel (https://github.com/sitedyno/phergie-fortune)
 *
 * @link https://github.com/sitedyno/phergie-fortune for the canonical source repository
 * @copyright Copyright (c) 2017 Heath Nail (https://github.com/sitedyno)
 * @license https://opensource.org/licenses/MIT MIT
 * @package Sitedyno\PhergieFortune
 */

namespace Sitedyno\PhergieFortune;

use Phergie\Irc\Bot\React\AbstractPlugin;
use Phergie\Irc\Bot\React\EventQueueInterface as Queue;
use Phergie\Irc\Plugin\React\Command\CommandEvent as Event;

/**
 * Plugin class.
 *
 * @category Sitedyno
 * @package Sitedyno\PhergieFortune
 */
class Plugin extends AbstractPlugin
{
    /**
     * Accepts plugin configuration.
     *
     * Supported keys:
     *
     *
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {

    }

    /**
     *
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            'command.' => 'handleCommand',
        ];
    }

    /**
     *
     *
     * @param \Phergie\Irc\Plugin\React\Command\CommandEvent $event
     * @param \Phergie\Irc\Bot\React\EventQueueInterface $queue
     */
    public function handleCommand(Event $event, Queue $queue)
    {
    }
}
