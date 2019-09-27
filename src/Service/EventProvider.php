<?php declare(strict_types=1);
/**
 * Joomla! Crowdin Synchronization Script
 *
 * @copyright  Copyright (C) 2016 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Joomla\Crowdin\Service;

use Joomla\Crowdin\EventListener\ConsoleEventSubscriber;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\Dispatcher;
use Joomla\Event\DispatcherInterface;
use Joomla\Event\SubscriberInterface;

/**
 * Event Service Provider
 */
final class EventProvider implements ServiceProviderInterface
{
	/**
	 * Registers the service provider with a DI container.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  void
	 */
	public function register(Container $container)
	{
		$container->alias(Dispatcher::class, DispatcherInterface::class)
			->share(DispatcherInterface::class, [$this, 'getDispatcherService'], true);

		$container->share(ConsoleEventSubscriber::class, [$this, 'getConsoleEventSubscriberService'], true)
			->tag('event.subscriber', [ConsoleEventSubscriber::class]);
	}

	/**
	 * Get the dispatcher service.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  DispatcherInterface
	 */
	public function getDispatcherService(Container $container): DispatcherInterface
	{
		$dispatcher = new Dispatcher;

		/** @var SubscriberInterface $subscriber */
		foreach ($container->getTagged('event.subscriber') as $subscriber)
		{
			$dispatcher->addSubscriber($subscriber);
		}

		return $dispatcher;
	}

	/**
	 * Get the console event subscriber service.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  ConsoleEventSubscriber
	 */
	public function getConsoleEventSubscriberService(Container $container): ConsoleEventSubscriber
	{
		return new ConsoleEventSubscriber;
	}
}
