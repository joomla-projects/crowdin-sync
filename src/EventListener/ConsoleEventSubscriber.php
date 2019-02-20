<?php
/**
 * Joomla! Crowdin Synchronization Script
 *
 * @copyright  Copyright (C) 2016 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Joomla\Crowdin\EventListener;

use Joomla\Console\ConsoleEvents;
use Joomla\Console\Event\ApplicationErrorEvent;
use Joomla\Event\SubscriberInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Event listener for console events
 */
final class ConsoleEventSubscriber implements SubscriberInterface
{
	/**
	 * Returns an array of events this subscriber will listen to.
	 *
	 * @return  array
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			ConsoleEvents::APPLICATION_ERROR => ['onApplicationError'],
		];
	}

	/**
	 * Handles the `console.application_error` event
	 *
	 * @param   ApplicationErrorEvent  $event  The emitted event
	 *
	 * @return  void
	 */
	public function onApplicationError(ApplicationErrorEvent $event)
	{
		$io = new SymfonyStyle($event->getApplication()->getConsoleInput(), $event->getApplication()->getConsoleOutput());
		$io->error($event->getError()->getMessage());
	}
}
