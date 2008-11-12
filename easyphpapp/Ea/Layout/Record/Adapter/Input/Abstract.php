<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Table
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.3.0.20081112
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Record/Adapter/Interface.php';

/**
 * Abstract class to get column cell content from record (array or object).
 */
abstract class Ea_Layout_Record_Adapter_Input_Abstract implements Ea_Layout_Record_Adapter_Interface
{
	protected $_field;
	protected $_baseId=null;
	protected $_config=null;
	
	public function __construct($field, $baseId=null, $config=null)
	{
		$this->_field=$field;
		if($baseId)$this->_baseId=(array)$baseId;
		$this->_config=$config;
	}
	
	/**
	 * This function must return content from record.
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
	
	public function getId($i)
	{
		if($this->_baseId) $id=$baseId;
		else $id=array();
		if($i!==null&&$this->_baseId) $id[]=$i;
		$id[]=$this->_field;
		return $id;
	}
}

?>