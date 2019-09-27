<?php declare(strict_types=1);
/**
 * Joomla! Crowdin Synchronization Script
 *
 * @copyright  Copyright (C) 2016 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Joomla\Crowdin;

/**
 * Crowdin Configuration Wrapper
 */
final class CrowdinConfiguration
{
	/**
	 * The API key.
	 *
	 * @var  string
	 */
	private $apiKey = '';

	/**
	 * The base path to the project files.
	 *
	 * @var  string
	 */
	private $basePath = '';

	/**
	 * The project files.
	 *
	 * @var  array
	 */
	private $files = [];

	/**
	 * The project identifier.
	 *
	 * @var  string
	 */
	private $identifier = '';

	/**
	 * CrowdinConfiguration constructor.
	 *
	 * This constructor is private to disallow instantiation without using the named constructor.
	 */
	private function __construct()
	{
	}

	/**
	 * Create a Configuration instance.
	 *
	 * @param   string  $identifier  The project identifier.
	 * @param   string  $apiKey      The API key.
	 * @param   string  $basePath    The base path to the project files.
	 * @param   array   $files       The project files.
	 *
	 * @return  CrowdinConfiguration
	 */
	public static function createConfiguration(string $identifier = '', string $apiKey = '', string $basePath = '', array $files = []): CrowdinConfiguration
	{
		$config = new self;

		$config->apiKey     = $apiKey;
		$config->basePath   = $basePath;
		$config->files      = $files;
		$config->identifier = $identifier;

		return $config;
	}

	/**
	 * Get the API key.
	 *
	 * @return  string
	 */
	public function getApiKey(): string
	{
		return $this->apiKey;
	}

	/**
	 * Get the base path to the project files.
	 *
	 * @return  string
	 */
	public function getBasePath(): string
	{
		return $this->basePath;
	}

	/**
	 * Get the project files.
	 *
	 * @return  array
	 */
	public function getFiles(): array
	{
		return $this->files;
	}

	/**
	 * Get the project identifier.
	 *
	 * @return  string
	 */
	public function getProjectIdentifier(): string
	{
		return $this->identifier;
	}
}
