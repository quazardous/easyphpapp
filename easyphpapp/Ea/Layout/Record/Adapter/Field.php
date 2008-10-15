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
 * Generic class to get column cell content from record (array or object).
 */
class Ea_Layout_Record_Adapter_Field implements Ea_Layout_Record_Adapter_Interface
{
	protected $_field;
	
	protected $_config;
	
	protected $_class;
	
	public function __construct($field, $config=null, $class='Ea_Layout_Table_Cell')
	{
		$this->_field=$field;
		$this->_config=$config;
		$this->_class=$class;
	}
	
	/**
	 * This function must return content from record.
	 * 
	 * @param array $record
	 * @return string
	 */
	public function getContent($record)
	{
		if(is_array($record))
		{
			return $record[$this->_field];
		}
		return $record->{$this->_field};
	}

	/**
	 * This function must return config for the cell constructor class.
	 * 
	 * @param mixed $record
	 * @return array
	 */
	function getConfig($record)
	{
		return $this->_config;
	}
	
	/**
	 * This function must return the cell class.
	 * 
	 * @param mixed $record
	 * @return string
	 */
	function getClass($record)
	{
		return $this->_class;
	}
}

?>