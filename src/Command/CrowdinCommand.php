<?php
/**
 * Joomla! Crowdin Synchronization Script
 *
 * @copyright  Copyright (C) 2016 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Joomla\Crowdin\Command;

use ElKuKu\Crowdin\Crowdin;
use Joomla\Crowdin\CrowdinConfiguration;
use Symfony\Component\Console\Command\Command;

/**
 * Base class for a Crowdin API command
 */
abstract class CrowdinCommand extends Command
{
	/**
	 * The Crowdin API connector.
	 *
	 * @var  Crowdin
	 */
	protected $crowdin;

	/**
	 * The Crowdin project configuration.
	 *
	 * @var  CrowdinConfiguration
	 */
	protected $crowdinConfiguration;

	/**
	 * Constructor.
	 *
	 * @param   Crowdin               $crowdin               The Crowdin API connector.
	 * @param   CrowdinConfiguration  $crowdinConfiguration  The Crowdin project configuration.
	 */
	public function __construct(Crowdin $crowdin, CrowdinConfiguration $crowdinConfiguration)
	{
		parent::__construct();

		$this->crowdin              = $crowdin;
		$this->crowdinConfiguration = $crowdinConfiguration;
	}
}
