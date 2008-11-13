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
	
	/**
	 * If defined, callback used in getValue().
	 * filterCallback($column, $value)
	 * 
	 * @var callback
	 * @see getValue()
	 */
	protected $_filter=null;
	
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
			$value=$record[$this->_field];
		}
		else
		{
			$value=$record->{$this->_field};
		}
		if($this->_filter) return call_user_func($this->_filter, $this->_field, $value);
		return $value;
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