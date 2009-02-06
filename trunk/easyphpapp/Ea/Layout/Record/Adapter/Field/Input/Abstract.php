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

/**
 * Abstract class to get column cell content from record (array or object).
 */
abstract class Ea_Layout_Record_Adapter_Field_Input_Abstract extends Ea_Layout_Record_Adapter_Field
{
	protected $_field;
	protected $_baseId=null;
	protected $_config=null;
	
	public function __construct($field, $baseId=null, $config=null)
	{
		$this->_field=$field;
		if($baseId)$this->_baseId=Ea_Layout_Input_Abstract::get_id_from_name($baseId);
		$this->_config=$config;
	}
	
	
	public function getId($i)
	{
		if($this->_baseId) $id=$this->_baseId;
		else $id=array();
		if($i!==null&&$this->_baseId) $id[]=$i;
		$id[]=$this->_field;
		return $id;
	}
}

?>