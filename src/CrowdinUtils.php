<?php declare(strict_types=1);
/**
 * Joomla! Crowdin Synchronization Script
 *
 * @copyright  Copyright (C) 2016 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Joomla\Crowdin;

/**
 * Crowdin Support Utilities
 */
abstract class CrowdinUtils
{
	/**
	 * A map of the language codes used within Crowdin with their Joomla! CMS language codes
	 *
	 * @var  array
	 */
	public const LANGUAGE_MAP = [
		'ar'    => 'ar-AA',
		'bg'    => 'bg-BG',
		'bn'    => 'bn-BD',
		'br-FR' => 'br-FR',
		'ca'    => 'ca-ES',
		'cs'    => 'cs-CZ',
		'da'    => 'da-DK',
		'de'    => 'de-DE',
		'el'    => 'el-GR',
		'en-US' => 'en-US',
		'es-CO' => 'es-CO',
		'es-ES' => 'es-ES',
		'et'    => 'et-EE',
		'fa'    => 'fa-IR',
		'fi'    => 'fi-FI',
		'fr'    => 'fr-FR',
		'fr-CA' => 'fr-CA',
		'ga-IE' => 'ga-IE',
		'he'    => 'he-IL',
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
		'ml-IN' => 'ml-IN',
		'mr'    => 'mr-IN',
		'ms'    => 'ms-MY',
		'nb'    => 'nb-NO',
		'nl'    => 'nl-NL',
		'nl-BE' => 'nl-BE',
		'pl'    => 'pl-PL',
		'pt-BR' => 'pt-BR',
		'pt-PT' => 'pt-PT',
		'ro'    => 'ro-RO',
		'ru'    => 'ru-RU',
		'si-LK' => 'si-LK',
		'sk'    => 'sk-SK',
		'sl'    => 'sl-SI',
		'sr'    => 'sr-RS',
		'sr-CS' => 'sr-CS',
		'sv-SE' => 'sv-SE',
		'ta'    => 'ta-IN',
		'th'    => 'th-TH',
		'tl'    => 'tl-PH',
		'tr'    => 'tr-TR',
		'ur-IN' => 'ur-IN',
		'zh-CN' => 'zh-CN',
		'zh-TW' => 'zh-TW',
	];

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
