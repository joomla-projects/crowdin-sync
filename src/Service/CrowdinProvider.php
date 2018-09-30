<?php
/**
 * Joomla! Crowdin Synchronization Script
 *
 * @copyright  Copyright (C) 2016 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Joomla\Crowdin\Service;

use ElKuKu\Crowdin\Crowdin;
use Joomla\Crowdin\CrowdinConfiguration;
use Joomla\DI\Container;
use Joomla\DI\Exception\DependencyResolutionException;
use Joomla\DI\ServiceProviderInterface;

/**
 * Crowdin Service Provider
 */
final class CrowdinProvider implements ServiceProviderInterface
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
		$container->share(Crowdin::class, [$this, 'getCrowdinService'], true);
	}

	/**
	 * Get the Crowdin service.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  Crowdin
	 */
	public function getCrowdinService(Container $container): Crowdin
	{
		if (!$container->has(CrowdinConfiguration::class))
		{
			throw new DependencyResolutionException(sprintf('The `%s` service has not been created.', CrowdinConfiguration::class));
		}

		/** @var CrowdinConfiguration $config */
		$config = $container->get(CrowdinConfiguration::class);

		return new Crowdin($config->getProjectIdentifier(), $config->getApiKey());
	}
}
