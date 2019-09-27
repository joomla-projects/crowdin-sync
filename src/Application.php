<?php declare(strict_types=1);
/**
 * Joomla! Crowdin Synchronization Script
 *
 * @copyright  Copyright (C) 2016 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Joomla\Crowdin;

use Joomla\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;

/**
 * Crowdin Console Application
 */
final class Application extends BaseApplication
{
	/**
	 * Builds the defauilt input definition.
	 *
	 * @return  InputDefinition
	 */
	protected function getDefaultInputDefinition(): InputDefinition
	{
		$definition = parent::getDefaultInputDefinition();

		$definition->addOption(
			new InputOption(
				'config-dir',
				'',
				InputOption::VALUE_OPTIONAL,
				'Specify the directory to the Crowdin configuration file'
			)
		);

		$definition->addOption(
			new InputOption(
				'project',
				'',
				InputOption::VALUE_OPTIONAL,
				'Specify the Crowdin project to process'
			)
		);

		$definition->addOption(
			new InputOption(
				'api-key',
				'',
				InputOption::VALUE_OPTIONAL,
				'Specify the Crowdin API key to use'
			)
		);

		return $definition;
	}
}
