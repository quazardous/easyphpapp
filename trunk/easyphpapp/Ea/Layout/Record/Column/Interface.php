<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Table
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.1
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

/**
 * Interface to get column cell content from record.
 */
interface Ea_Layout_Record_Column_Interface
{
	/**
	 * This function must return content from record.
	 * 
	 * @param mixed $record
	 * @return mixed
	 */
	function getContent($record);
	
	/**
	 * This function must return config for the cell constructor class.
	 * 
	 * @param mixed $record
	 * @return array
	 */
	function getConfig($record);
	
	/**
	 * This function must return the cell class.
	 * 
	 * @param mixed $record
	 * @return string
	 */
	function getClass($record);
}

?>