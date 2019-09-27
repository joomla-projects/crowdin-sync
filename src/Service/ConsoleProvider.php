<?php declare(strict_types=1);
/**
 * Joomla! Crowdin Synchronization Script
 *
 * @copyright  Copyright (C) 2016 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Joomla\Crowdin\Service;

use ElKuKu\Crowdin\Crowdin;
use Joomla\Console\Loader\ContainerLoader;
use Joomla\Console\Loader\LoaderInterface;
use Joomla\Crowdin\Command\DownloadCommand;
use Joomla\Crowdin\Command\UploadCommand;
use Joomla\Crowdin\CrowdinConfiguration;
use Joomla\DI\Container;
use Joomla\DI\Exception\DependencyResolutionException;
use Joomla\DI\ServiceProviderInterface;

/**
 * Console Service Provider
 */
final class ConsoleProvider implements ServiceProviderInterface
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
		$container->alias(ContainerLoader::class, LoaderInterface::class)
			->share(LoaderInterface::class, [$this, 'getCommandLoaderService'], true);

		$container->share(DownloadCommand::class, [$this, 'getDownloadCommandService'], true);
		$container->share(UploadCommand::class, [$this, 'getUploadCommandService'], true);
	}

	/**
	 * Get the command loader service.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  LoaderInterface
	 */
	public function getCommandLoaderService(Container $container): LoaderInterface
	{
		$mapping = [
			'crowdin:download' => DownloadCommand::class,
			'crowdin:upload'   => UploadCommand::class,
		];

		return new ContainerLoader($container, $mapping);
	}

	/**
	 * Get the download command service.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  DownloadCommand
	 */
	public function getDownloadCommandService(Container $container): DownloadCommand
	{
		if (!$container->has(CrowdinConfiguration::class))
		{
			throw new DependencyResolutionException(sprintf('The `%s` service has not been created.', CrowdinConfiguration::class));
		}

		return new DownloadCommand(
			$container->get(Crowdin::class),
			$container->get(CrowdinConfiguration::class)
		);
	}

	/**
	 * Get the upload command service.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  UploadCommand
	 */
	public function getUploadCommandService(Container $container): UploadCommand
	{
		if (!$container->has(CrowdinConfiguration::class))
		{
			throw new DependencyResolutionException(sprintf('The `%s` service has not been created.', CrowdinConfiguration::class));
		}

		return new UploadCommand(
			$container->get(Crowdin::class),
			$container->get(CrowdinConfiguration::class)
		);
	}
}
