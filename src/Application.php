<?php
/**
 * Joomla! Crowdin Synchronization Script
 *
 * @copyright  Copyright (C) 2016 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Joomla\Crowdin;

use Joomla\Console\Application as BaseApplication;
use Joomla\Console\Loader\LoaderInterface;
use Joomla\Crowdin\Service\ConsoleProvider;
use Joomla\Crowdin\Service\CrowdinProvider;
use Joomla\DI\Container;
use Joomla\DI\ContainerAwareInterface;
use Joomla\DI\ContainerAwareTrait;
use Joomla\DI\Exception\DependencyResolutionException;
use Joomla\Registry\Registry;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Crowdin Console Application
 */
final class Application extends BaseApplication implements ContainerAwareInterface
{
	use ContainerAwareTrait;

	/**
	 * Application constructor.
	 */
	public function __construct()
	{
		parent::__construct('Joomla! Crowdin Synchronisation Tool');

		$this->container = new Container;
		$this->container->registerServiceProvider(new ConsoleProvider);
		$this->container->registerServiceProvider(new CrowdinProvider);

		$this->setCommandLoader($this->container->get(CommandLoaderInterface::class));
	}

	/**
	 * Configures the Crowdin configuration service within the container.
	 *
	 * @param   InputInterface   $input   The application's input interface.
	 * @param   OutputInterface  $output  The application's input interface.
	 *
	 * @return  void
	 */
	private function configureCrowdinService(InputInterface $input, OutputInterface $output)
	{
		$configDir = $input->getOption('config-dir');

		$crowdinFile = false;

		if (is_dir($configDir))
		{
			$file = $configDir . '/crowdin.yaml';

			if (is_file($file))
			{
				$crowdinFile = $file;
			}
		}
		elseif (file_exists('crowdin.yaml'))
		{
			$crowdinFile = realpath('crowdin.yaml');
		}

		if ($crowdinFile === false)
		{
			throw new \InvalidArgumentException('The Crowdin configuration file could not be found.');
		}

		$this->container->share(
			CrowdinConfiguration::class,
			function (Container $container) use ($crowdinFile, $input)
			{
				$registry = new Registry;
				$registry->loadFile($crowdinFile, 'YAML');

				$identifier = (string) ($input->getOption('project') ?: $registry->get('project_identifier'));
				$basePath   = CrowdinUtils::trimPath((string) $registry->get('base_path'));
				$files      = $registry->get('files', []);

				// Check if an API key was given through the options otherwise look for the environment variable
				$apiKey = $input->getOption('api-key');

				if (!$apiKey)
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

	/**
	 * Gets the default input definition.
	 *
	 * @return  InputDefinition
	 */
	protected function getDefaultInputDefinition()
	{
		$definition = parent::getDefaultInputDefinition();

		$definition->addOption(
			new InputOption(
				'--config-dir',
				'',
				InputOption::VALUE_OPTIONAL,
				'Specify the directory to the Crowdin configuration file'
			)
		);

		$definition->addOption(
			new InputOption(
				'--project',
				'',
				InputOption::VALUE_OPTIONAL,
				'Specify the Crowdin project to process'
			)
		);

		$definition->addOption(
			new InputOption(
				'--api-key',
				'',
				InputOption::VALUE_OPTIONAL,
				'Specify the Crowdin API key to use'
			)
		);

		return $definition;
	}

	/**
	 * Runs the current application.
	 *
	 * @param   InputInterface   $input   The application's input interface.
	 * @param   OutputInterface  $output  The application's input interface.
	 *
	 * @return  integer
	 */
	public function doRun(InputInterface $input, OutputInterface $output)
	{
		$input->bind($this->getDefinition());

		$this->configureCrowdinService($input, $output);

		return parent::doRun($input, $output);
	}
}
