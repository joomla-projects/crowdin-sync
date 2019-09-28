<?php declare(strict_types=1);
/**
 * Joomla! Crowdin Synchronization Script
 *
 * @copyright  Copyright (C) 2016 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Joomla\Crowdin\Tests;

use Joomla\Crowdin\CrowdinUtils;
use PHPUnit\Framework\TestCase;

/**
 * Test class for Joomla\Crowdin\CrowdinUtils
 */
final class CrowdinUtilsTest extends TestCase
{
	/**
	 * Data provider for tests trimming paths
	 *
	 * @return  \Generator
	 */
	public function dataTrimPath(): \Generator
	{
		yield 'trimmed path' => ['./cdn/layouts/footer/en-GB.footer.html', 'cdn/layouts/footer/en-GB.footer.html'];
		yield 'not trimmed path' => ['cdn/layouts/footer/en-GB.menu.html', 'cdn/layouts/footer/en-GB.menu.html'];
	}

	/**
	 * @testdox Validates a path is correctly trimmed
	 *
	 * @param   string  $input          The input string
	 * @param   string  $expectedOutput The expected output string
	 *
	 * @dataProvider  dataTrimPath
	 */
	public function testTrimPath(string $input, string $expectedOutput)
	{
		$this->assertSame($expectedOutput, CrowdinUtils::trimPath($input));
	}
}
