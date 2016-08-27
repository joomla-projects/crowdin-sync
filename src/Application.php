<?php
/**
 * Joomla! Crowdin Synchronization Script
 *
 * @copyright  Copyright (C) 2016 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Joomla\Crowdin;

use ElKuKu\Crowdin\Crowdin;
use ElKuKu\Crowdin\Languagefile;
use Joomla\Application\AbstractCliApplication;
use Joomla\Registry\Registry;

/**
 * Crowdin CLI Application
 */
final class Application extends AbstractCliApplication
{
	/**
	 * Crowdin API connector
	 *
	 * @var  Crowdin
	 */
	private $crowdin;

	/**
	 * A map of the language codes used within Crowdin with their Joomla! CMS language codes
	 *
	 * @var  array
	 */
	private $languageMap = [
		'ar'    => 'ar-AA',
		'bg'    => 'bg-BG',
		'da'    => 'da-DK',
		'de'    => 'de-DE',
		'el'    => 'el-GR',
		'en-US' => 'en-US',
		'es-CO' => 'es-CO',
		'es-ES' => 'es-ES',
		'et'    => 'et-EE',
		'fi'    => 'fi-FI',
		'fr'    => 'fr-FR',
		'fr-CA' => 'fr-CA',
		'hi'    => 'hi-IN',
		'hr'    => 'hr-HR',
		'hu'    => 'hu-HU',
		'id'    => 'id-ID',
		'is'    => 'is-IS',
		'it'    => 'it-IT',
		'ja'    => 'ja-JA',
		'ka'    => 'ka-GE',
		'lv'    => 'lv-LV',
		'mk'    => 'mk-MK',
		'nb'    => 'nb-NO',
		'nl'    => 'nl-NL',
		'pl'    => 'pl-PL',
		'pt-BR' => 'pt-BR',
		'pt-PT' => 'pt-PT',
		'ro'    => 'ro-RO',
		'sk'    => 'sk-SK',
		'sl'    => 'sl-SI',
		'sv-SE' => 'sv-SE',
		'th'    => 'th-TH',
		'zh-TW' => 'zh-TW',
	];

	/**
	 * Project YAML configuration
	 *
	 * @var  Registry
	 */
	private $yamlConfig;

	/**
	 * Application constructor
	 */
	public function __construct()
	{
		parent::__construct();

		// Try to find our Crowdin YAML config, optionally allow a path to be specified
		$yamlPath = getcwd() . '/' . $this->input->getPath('crowdin-config', 'crowdin.yaml');

		if (!file_exists($yamlPath))
		{
			throw new \RuntimeException(
				sprintf(
					'YAML configuration file not found at "%s"',
					$yamlPath
				)
			);
		}

		// Create a Registry instance with the config for easy reference
		$this->yamlConfig = (new Registry)->loadFile($yamlPath, 'YAML');

		// Grab our project identifier
		$crowdinProject = $this->input->getString('crowdin-project', $this->yamlConfig->get('project_identifier'));

		// Check if an API key was given via CLI otherwise look for the environment variable and pull the config from that
		if ($this->input->exists('crowdin-api-key'))
		{
			$crowdinApiKey = $this->input->getString('crowdin-api-key');
		}
		else
		{
			$crowdinApiKey = getenv($this->yamlConfig->get('api_key_env'));
		}

		// Make sure we have both values otherwise we can't continue
		if (!$crowdinProject || !$crowdinApiKey)
		{
			throw new \InvalidArgumentException('Missing required parameters for the Crowdin API connection.');
		}

		$this->crowdin = new Crowdin($crowdinProject, $crowdinApiKey);
	}

	/**
	 * Method to run the application routines.
	 *
	 * @return  void
	 */
	protected function doExecute()
	{
		if ($this->input->getBool('download'))
		{
			$this->downloadFiles();
		}
		elseif ($this->input->getBool('upload'))
		{
			$this->uploadFiles();
		}
		else
		{
			throw new \InvalidArgumentException('Must specify "--download" or "--upload" operation');
		}
	}

	/**
	 * Download files from Crowdin
	 *
	 * @return  void
	 */
	private function downloadFiles()
	{
		// Get a list of this project's languages
		$projectInfoResponse = $this->crowdin->project->getInfo();

		if ($projectInfoResponse->getStatusCode() !== 200)
		{
			throw new \RuntimeException(
				sprintf(
					'Invalid response code "%1$d" from Crowdin API with message: %2$s',
					$projectInfoResponse->getStatusCode(),
					(string) $projectInfoResponse->getBody()
				)
			);
		}

		$projectInfo = simplexml_load_string((string) $projectInfoResponse->getBody());

		$basePath = $this->getBaseFilePath();

		foreach ($projectInfo->languages->item as $language)
		{
			foreach ($this->yamlConfig->get('files') as $file)
			{
				// Finish the file name and replace the placeholders
				$filePath = $basePath . strtr($file->translation, ['%locale%' => $this->languageMap[(string) $language->code]]);

				// Make sure the directory exists
				if (!is_dir(dirname($filePath)))
				{
					if (false === mkdir(dirname($filePath)))
					{
						throw new \RuntimeException('Could not create directory at: ' . dirname($filePath));
					}
				}

				// Now download the file
				$this->crowdin->file->export($file->dest, (string) $language->code, $filePath);

				// Success!
				$this->out(sprintf('<info>Downloaded %s</info>', basename($filePath)));
			}
		}
	}

	/**
	 * Get the base filesystem path
	 *
	 * @return  string
	 */
	private function getBaseFilePath()
	{
		$configBase = ltrim($this->yamlConfig->get('base_path'), './');

		return getcwd() . '/' . $configBase;
	}

	/**
	 * Upload files to Crowdin
	 *
	 * @return  void
	 */
	private function uploadFiles()
	{
		$basePath = $this->getBaseFilePath();

		foreach ($this->yamlConfig->get('files') as $file)
		{
			// Finish the file name and replace the placeholders
			$filePath = $basePath . $file->source;

			// Now upload the file
			$this->crowdin->file->update(new Languagefile($filePath, $file->dest));

			// Success!
			$this->out(sprintf('<info>Uploaded %s</info>', basename($filePath)));
		}
	}
}
