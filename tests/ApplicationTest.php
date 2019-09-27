<?php declare(strict_types=1);
/**
 * Joomla! Crowdin Synchronization Script
 *
 * @copyright  Copyright (C) 2016 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Joomla\Crowdin\Tests;

use Joomla\Crowdin\Application;
use PHPUnit\Framework\TestCase;

/**
 * Test class for Joomla\Crowdin\ApplicationFactory
 */
final class ApplicationTest extends TestCase
{
	/**
	 * @testdox Validates the default input definition is extended with the application's extra options
	 */
	public function testInputDefinitionIsExtended()
	{
		$inputDefinition = (new Application)->getDefinition();

		$this->assertTrue($inputDefinition->hasOption('config-dir'));
		$this->assertTrue($inputDefinition->hasOption('project'));
		$this->assertTrue($inputDefinition->hasOption('api-key'));
	}
}
