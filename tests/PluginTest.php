<?php
/**
 * Phergie plugin for Display a fortune in channel (https://github.com/sitedyno/phergie-fortune)
 *
 * @link https://github.com/sitedyno/phergie-fortune for the canonical source repository
 * @copyright Copyright (c) 2017 Heath Nail (https://github.com/sitedyno)
 * @license https://opensource.org/licenses/MIT MIT
 * @package Sitedyno\PhergieFortune
 */

namespace Sitedyno\PhergieFortune\Tests;

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
     * Tests that getSubscribedEvents() returns an array.
     */
    public function testGetSubscribedEvents()
    {
        $plugin = new Plugin;
        $this->assertInternalType('array', $plugin->getSubscribedEvents());
    }
}
