<?php declare(strict_types=1);
/**
 * Joomla! Crowdin Synchronization Script
 *
 * @copyright  Copyright (C) 2016 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Joomla\Crowdin\Service;

use Joomla\Application\AbstractApplication;
use Joomla\Application\ApplicationInterface;
use Joomla\Console\Application as BaseConsoleApplication;
use Joomla\Console\Loader\LoaderInterface;
use Joomla\Crowdin\Application;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;

/**
 * Application Service Provider
 */
final class ApplicationProvider implements ServiceProviderInterface
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
		$container->alias(Application::class, ApplicationInterface::class)
			->alias(BaseConsoleApplication::class, ApplicationInterface::class)
			->alias(AbstractApplication::class, ApplicationInterface::class)
			->share(ApplicationInterface::class, [$this, 'getApplicationService'], true);
	}

	/**
	 * Get the application service.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  ApplicationInterface
	 */
	public function getApplicationService(Container $container): ApplicationInterface
	{
		$application = new Application;
		$application->setCommandLoader($container->get(LoaderInterface::class));
		$application->setDispatcher($container->get(DispatcherInterface::class));
		$application->setName('Joomla! Crowdin Synchronisation Tool');

		return $application;
	}
}
