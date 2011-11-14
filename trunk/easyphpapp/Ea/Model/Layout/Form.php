<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Model
 * @subpackage  Form
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id:$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Model/Layout.php';
require_once 'Ea/Layout/Input/Abstract.php';

/**
 * Form model class.
 * This class handle the layout description form data model.
 *
 */
class Ea_Model_Layout_Form extends Ea_Model_Layout
{

	/**
	 * If given, name of the column usable to get the index of the inputs names.
	 * @see Ea_Layout_Record_Adapter_Field_Input_Abstract::getIndex()
	 * 
	 * @var string
	 */
	protected $_recordIndexColumn=null;
	
	public function setRecordIndexColumn($column)
	{
		$this->_recordIndexColumn=$column;
	}
	
	public function getRecordIndexColumn()
	{
		return $this->_recordIndexColumn;
	}
	
	protected $_baseId=null;
	
	public function setBaseId($baseId, $column=null)
	{
		require_once 'Ea/Layout/Input/Abstract.php';
		$this->_baseId=Ea_Layout_Input_Abstract::get_id_from_name($baseId);
		if ($column!==null) $this->setRecordIndexColumn($column);
	}
	
	public function getBaseId()
	{
		return $this->_baseId;
	}
	
	protected $_defaultRecordAdapterName = 'Input_Default';
	protected $_defaultRecordAdapterNameByType=array(
		'string'   => 'Input_String',
		'text'   	 => 'Input_Text',
	  'number'   => 'Input_Number',
		'enum'     => 'Input_Enum',
		'boolean'  => 'Input_Boolean',
		'date'   	 => 'Input_Date',
		'datetime' => 'Input_Date',
	);

	protected $_defaultRecordAdapterReadonlyName = 'String';
	protected $_defaultRecordAdapterReadonlyNameByType=array(
		'string' => 'String',
		'enum' => 'Enum',
	  'boolean' => 'Boolean',
	);	

	protected function getDefaultRecordAdapterNameByType($type, $column)
	{
	  if ($this->getColumnAdapterReadonly($column)) {
	    if(array_key_exists($type, $this->_defaultRecordAdapterReadonlyNameByType)) return $this->_defaultRecordAdapterReadonlyNameByType[$type];
		  return $this->_defaultRecordAdapterReadonlyName;
	  }
		return parent::getDefaultRecordAdapterNameByType($type, $column);
	}	
	
	/**
	 * Apply special filter on record values.
	 * 
	 * @param string $column
	 * @param string $value
	 * @param mixed $data
	 * @return string
	 * 
	 * @see Ea_Layout_Record_Adapter_Field::getRawValue()
	 */
	public function filterRecordValue($column, $value, $data=null)
	{
		switch($this->getColumnType($column))
		{
			case 'date': case 'datetime':
				if($this->_dataModel)	{
					$value = $this->_dataModel->filterRecordValue($column, $value, $data);
				}
				else {
				  $value = parent::filterRecordValue($column, $value, $data);
				}
				return $value;
			default: return parent::filterRecordValue($column, $value, $data);
		}
	}
	
}