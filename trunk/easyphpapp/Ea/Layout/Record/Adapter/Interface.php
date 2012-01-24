<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Table
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

/**
 * Interface to get column cell content from record.
 */
interface Ea_Layout_Record_Adapter_Interface
{
	/**
	 * This function must return content from record.
	 * 
	 * @param mixed $record
	 * @param integer $i record number
	 * @return mixed
	 */
	function getContent($record, $i);

	/**
	 * This function must return raw field content from record.
	 * 
	 * @param array $record
	 * @return string
	 */
	public function getRawValue($record);
	//TODO : think about it
	
}
