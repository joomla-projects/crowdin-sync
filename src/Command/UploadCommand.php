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

/**
 * Command to upload project files to Crowdin.
 */
final class UploadCommand extends CrowdinCommand
{
	/**
	 * Execute the command.
	 *
	 * @return  integer  The exit code for the command.
	 */
	public function execute(): int
	{
		$symfonyStyle = $this->createSymfonyStyle();

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
	 * Initialise the command.
	 *
	 * @return  void
	 */
	protected function initialise()
	{
		$this->setName('crowdin:upload');
		$this->setDescription('Upload project files to Crowdin');
	}
}
