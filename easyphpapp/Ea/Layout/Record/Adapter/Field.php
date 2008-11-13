<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Table
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.3.0.20081113
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Record/Adapter/Interface.php';

/**
 * Generic class to get column cell input from record (array or object).
 */
class Ea_Layout_Record_Adapter_Field implements Ea_Layout_Record_Adapter_Interface
{
	protected $_field;
	
	public function __construct($field)
	{
		$this->_field=$field;
	}

	/**
	 * This function must return field content from record.
	 * 
	 * @param array $record
	 * @return string
	 */
	public function getValue($record)
	{
		if(is_array($record))
		{
			return $record[$this->_field];
		}
		return $record->{$this->_field};
	}
	
	/**
	 * This function must return content from record.
	 * 
	 * @param array $record
	 * @param integer $i record number
	 * @return string
	 */
	public function getContent($record, $i)
	{
		return $this->getValue($record);
	}
}

?>