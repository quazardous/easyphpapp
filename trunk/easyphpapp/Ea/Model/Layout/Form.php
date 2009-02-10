<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Model
 * @subpackage  Form
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.3.4-20090123
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
	
	public function setBaseId($baseId)
	{
		$this->_baseId=Ea_Layout_Input_Abstract::get_id_from_name($baseId);
	}
	
	public function getBaseId()
	{
		return $this->_baseId;
	}
	
	protected $_defaultRecordAdapterName = 'Input_Default';
	protected $_defaultRecordAdapterNameByType=array(
		'string' => 'Input_String',
		'text'   => 'Input_Text',
		'enum'   => 'Input_Enum',
		'boolean'   => 'Input_Boolean',
	);
	
}