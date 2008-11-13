<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Model
 * @subpackage  Form
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.3.0.20081106
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Model/Layout.php';

/**
 * Form model class.
 * This class handle the layout description form data model.
 *
 */
class Ea_Model_Layout_Form extends Ea_Model_Layout
{

	protected $_baseId=null;
	
	public function setBaseId($baseId)
	{
		$this->_baseId=$baseId;
	}
	
	public function getBaseId()
	{
		return $this->_baseId;
	}
	
	protected $_defaultRecordAdapterClass = 'Ea_Model_Layout_Record_Adapter_Input_Default';
	protected $_defaultRecordAdapterClassByType=array(
		self::type_string => 'Ea_Model_Layout_Record_Adapter_Input_String',
		self::type_text => 'Ea_Model_Layout_Record_Adapter_Input_Text',
		self::type_enum   => 'Ea_Model_Layout_Record_Adapter_Input_Enum',
	);
	
}