<?php
/**
 * Joomla! Crowdin Synchronization Script
 *
 * @copyright  Copyright (C) 2016 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Joomla\Crowdin;

/**
 * Crowdin Support Utilities
 */
abstract class CrowdinUtils
{
	/**
	 * CrowdinUtils constructor.
	 *
	 * This class cannot be instantiated.
	 */
	private function __construct()
	{
	}

	/**
	 * Trim the file path.
	 *
	 * @param   string  $path  Path to trim.
	 *
	 * @return  string
	 */
	public static function trimPath(string $path): string
	{
		// Trim if the first characters are './'
		if (substr($path, 0, 2) === './')
		{
			return substr($path, 2);
		}

		return $path;
	}
}
