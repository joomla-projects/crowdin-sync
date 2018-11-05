<?php
/**
 * Joomla! Crowdin Synchronization Script
 *
 * @copyright  Copyright (C) 2016 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Joomla\Crowdin\Command;

use ElKuKu\Crowdin\Languagefile;
use Joomla\Crowdin\CrowdinUtils;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Command to upload project files to Crowdin.
 */
final class UploadCommand extends CrowdinCommand
{
	/**
	 * The default command name
	 *
	 * @var  string
	 */
	protected static $defaultName = 'crowdin:upload';

	/**
	 * Executes the current command.
	 *
	 * @return integer|null  null or 0 if everything went fine, or an error code
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$symfonyStyle = new SymfonyStyle($input, $output);

		$basePath = $this->crowdinConfiguration->getBasePath();

		foreach ($this->crowdinConfiguration->getFiles() as $file)
		{
			// Finish the file name and replace the placeholders
			$filePath = realpath($basePath . CrowdinUtils::trimPath($file->source));

			// Now upload the file
			$this->crowdin->file->update(new Languagefile($filePath, $file->dest));

			// Success!
			$symfonyStyle->comment(sprintf('Uploaded %s', basename($filePath)));
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
		$this->setDescription('Upload project files to Crowdin');
	}
}
