<?php declare(strict_types=1);
/**
 * Joomla! Crowdin Synchronization Script
 *
 * @copyright  Copyright (C) 2016 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Joomla\Crowdin\EventListener;

use Joomla\Application\ApplicationEvents;
use Joomla\Application\Event\ApplicationEvent;
use Joomla\Console\ConsoleEvents;
use Joomla\Console\Event\ApplicationErrorEvent;
use Joomla\Crowdin\Application;
use Joomla\Crowdin\CrowdinConfiguration;
use Joomla\Crowdin\CrowdinUtils;
use Joomla\DI\Container;
use Joomla\DI\ContainerAwareInterface;
use Joomla\DI\ContainerAwareTrait;
use Joomla\DI\Exception\DependencyResolutionException;
use Joomla\Event\SubscriberInterface;
use Joomla\Registry\Registry;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Event listener for console events
 */
final class ConsoleEventSubscriber implements SubscriberInterface, ContainerAwareInterface
{
	use ContainerAwareTrait;

	/**
	 * Returns an array of events this subscriber will listen to.
	 *
	 * @return  array
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			ApplicationEvents::BEFORE_EXECUTE => ['onBeforeExecute'],
			ConsoleEvents::APPLICATION_ERROR  => ['onApplicationError'],
		];
	}

	/**
	 * Handles the `console.application_error` event
	 *
	 * @param   ApplicationErrorEvent  $event  The emitted event
	 *
	 * @return  void
	 */
	public function onApplicationError(ApplicationErrorEvent $event): void
	{
		$io = new SymfonyStyle($event->getApplication()->getConsoleInput(), $event->getApplication()->getConsoleOutput());
		$io->error($event->getError()->getMessage());
	}

	/**
	 * Handles the `application.before_execute` event
	 *
	 * @param   ApplicationEvent  $event  The emitted event
	 *
	 * @return  void
	 */
	public function onBeforeExecute(ApplicationEvent $event): void
	{
		if ($this->container->has(CrowdinConfiguration::class))
		{
			return;
		}

		$this->container->share(
			CrowdinConfiguration::class,
			function (Container $container)
			{
				/** @var Application $application */
				$application = $container->get(Application::class);

				$input = $application->getConsoleInput();

				$crowdinFile = false;

				if ($input->hasOption('config-dir'))
				{
					$configDir = $input->getOption('config-dir');

					if (is_dir($configDir))
					{
						$file = $configDir . '/crowdin.yaml';

						if (is_file($file))
						{
							$crowdinFile = $file;
						}
					}
				}
				elseif (file_exists('crowdin.yaml'))
				{
					$crowdinFile = realpath('crowdin.yaml');
				}

				if ($crowdinFile === false)
				{
					throw new DependencyResolutionException('The Crowdin configuration file could not be found.');
				}

				$registry = new Registry;
				$registry->loadFile($crowdinFile, 'YAML');

				$identifier = (string) ($input->hasOption('project') ? $input->getOption('project') : $registry->get('project_identifier'));
				$basePath   = CrowdinUtils::trimPath((string) $registry->get('base_path'));
				$files      = $registry->get('files', []);

				// Check if an API key was given through the options otherwise look for the environment variable
				$apiKey = $input->hasOption('api-key') ? $input->getOption('api-key') : false;

				if ($apiKey === false)
				{
					$apiKey = getenv($registry->get('api_key_env'));

					if ($apiKey === false)
					{
						throw new DependencyResolutionException(
							sprintf('The environment variable `%s` is not defined.', $registry->get('api_key_env'))
						);
					}
				}

				return CrowdinConfiguration::createConfiguration($identifier, $apiKey, $basePath, $files);
			},
			true
		);
	}
}
