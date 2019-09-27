<?php declare(strict_types=1);
/**
 * Joomla! Crowdin Synchronization Script
 *
 * @copyright  Copyright (C) 2016 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Joomla\Crowdin\Tests;

use Joomla\Crowdin\Application;
use Joomla\Crowdin\ApplicationFactory;
use PHPUnit\Framework\TestCase;

/**
 * Test class for Joomla\Crowdin\ApplicationFactory
 */
final class ApplicationFactoryTest extends TestCase
{
	/**
	 * @testdox Validates the console application is created
	 */
	public function testCreateApplication()
	{
		$this->assertInstanceOf(Application::class, ApplicationFactory::createApplication());
	}

	/**
	 * @testdox Validates a new console application is always created
	 */
	public function testCreateApplicationAlwaysCreatesNewInstance()
	{
		$this->assertNotSame(ApplicationFactory::createApplication(), ApplicationFactory::createApplication());
	}
}
