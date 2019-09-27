<?php declare(strict_types=1);
/**
 * Joomla! Crowdin Synchronization Script
 *
 * @copyright  Copyright (C) 2016 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Joomla\Crowdin\Command;

use Joomla\Crowdin\CrowdinUtils;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Command to download project files to Crowdin.
 */
final class DownloadCommand extends CrowdinCommand
{
	/**
	 * The default command name
	 *
	 * @var  string
	 */
	protected static $defaultName = 'crowdin:download';

	/**
	 * Internal function to execute the command.
	 *
	 * @param   InputInterface   $input   The input to inject into the command.
	 * @param   OutputInterface  $output  The output to inject into the command.
	 *
	 * @return  integer  The command exit code
	 */
	protected function doExecute(InputInterface $input, OutputInterface $output): int
	{
		$symfonyStyle = new SymfonyStyle($input, $output);

		// Get a list of this project's languages
		$projectInfoResponse = $this->crowdin->project->getInfo();

		if ($projectInfoResponse->getStatusCode() !== 200)
		{
			$symfonyStyle->error(
				sprintf(
					'Invalid response code "%1$d" from Crowdin API with message: %2$s',
					$projectInfoResponse->getStatusCode(),
					(string) $projectInfoResponse->getBody()
				)
			);

			return 1;
		}

		$projectInfo = simplexml_load_string((string) $projectInfoResponse->getBody());

		$basePath    = $this->crowdinConfiguration->getBasePath();
		$languageMap = CrowdinUtils::getLanguageMap();

		foreach ($projectInfo->languages->item as $language)
		{
			foreach ($this->crowdinConfiguration->getFiles() as $file)
			{
				$langCode = (string) $language->code;

				// Make sure the language exists in the mapping array
				if (!isset($languageMap[$langCode]))
				{
					$symfonyStyle->warning(sprintf('Missing language code `%s` in mapping array', $langCode));

					continue;
				}

				// Finish the file name and replace the placeholders
				$filePath = $basePath . strtr(CrowdinUtils::trimPath($file->translation), ['%locale%' => $languageMap[$langCode]]);

				// Make sure the directory exists
				if (!is_dir(dirname($filePath)))
				{
					if (!mkdir(dirname($filePath)))
					{
						throw new \RuntimeException('Could not create directory at: ' . dirname($filePath));
					}
				}

				// Now download the file
				$this->crowdin->file->export($file->dest, (string) $language->code, $filePath);

				// Success!
				$symfonyStyle->comment(sprintf('Downloaded %s', basename($filePath)));
			}
		}

		return 0;
	}

	/**
	 * Configures the current command.
	 *
	 * @return  void
	 */
	protected function configure()
	{
		$this->setDescription('Download project files to Crowdin');
	}
}
