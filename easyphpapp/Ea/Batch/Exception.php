<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Batch
 * @subpackage  Base
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.3.3-20081211
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

/**
 * Exception class for Ea_Batch_Abstract.
 * 
 * @see Ea_Batch_Abstract
 */

class Ea_Batch_Exception extends Exception
{
	const invalid_param           = 0;
	const stop                    = 1;
	const unable_to_open_log_file = 2;
	
	protected $_exitCode;
	
	function __construct($message, $code, $exitCode = -1)
	{
		parent::__construct($message, $code);
		$this->_exitCode=$exitCode;
	}
	
	function getExitCode()
	{
		return $this->_exitCode;
	}
}