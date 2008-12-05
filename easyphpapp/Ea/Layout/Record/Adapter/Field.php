<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Table
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.3.2-20081203
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Record/Adapter/Interface.php';

/**
 * Field value from record (array or object).
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
		if(is_array($record)||$record instanceof ArrayAccess)
		{
			$value=$record[$this->_field];
		}
		else if(is_object($record))
		{
			$value=$record->{$this->_field};
		}
		else $value=null;
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