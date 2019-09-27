<?php declare(strict_types=1);
/**
 * Joomla! Crowdin Synchronization Script
 *
 * @copyright  Copyright (C) 2016 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Joomla\Crowdin;

use Joomla\Crowdin\Service\ApplicationProvider;
use Joomla\Crowdin\Service\ConsoleProvider;
use Joomla\Crowdin\Service\CrowdinProvider;
use Joomla\Crowdin\Service\EventProvider;
use Joomla\DI\Container;

/**
 * Factory class to build the console application
 */
abstract class ApplicationFactory
{
	/**
	 * ApplicationFactory constructor.
	 *
	 * This class cannot be instantiated.
	 */
	private function __construct()
	{
	}

	/**
	 * Create a console application instance.
	 *
	 * @return  Application
	 */
	public static function createApplication(): Application
	{
		$container = new Container;
		$container->registerServiceProvider(new ApplicationProvider);
		$container->registerServiceProvider(new ConsoleProvider);
		$container->registerServiceProvider(new CrowdinProvider);
		$container->registerServiceProvider(new EventProvider);

		return $container->get(Application::class);
	}
}
