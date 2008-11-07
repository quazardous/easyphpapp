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

require_once 'Ea/Model/Abstract.php';
require_once 'Ea/Model/Data/Abstract.php';

/**
 * Form model class.
 * This class handle the layout description form data model.
 *
 */
class Ea_Model_Form extends Ea_Model_Abstract
{
	
	/**
	 * Meta info on columns.
	 * array(
	 *   'field name'=>array(
	 *      'adapter'		    => array('class'=>'Ea_Layout_Record_Adapter_Interface+Ea_Model_Form_Record_Adapter_Interface', 'config'=>$congig_for_the_adapter),
	 *   ),
	 * )
	 * 
	 * @var array
	 * @see Ea_Model_Abstract::$_metadata
	 */
	//protected $_metadata=null;
	
	/**
	 * Data model.
	 * 
	 * @var Ea_Model_Data_Abstract
	 */
	protected $_dataModel=null;
	public function setDataModel(Ea_Model_Data_Abstract $model)
	{
		$this->_dataModel=$model;
	}
	
	protected $_defaultRecordAdapterClass = 'Ea_Model_Form_Record_Adapter_Text';
	protected $_defaultRecordAdapterClassByType=array(
		self::type_string => 'Ea_Model_Form_Record_Adapter_Text',
	);
	
	protected function getDefaultRecordAdapterClassByType($type)
	{
		if(array_key_exists($type, $this->_defaultRecordAdapterClassByType)) return $this->_defaultRecordAdapterClassByType[$type];
		return $this->_defaultRecordAdapterClass;
	}
	
	protected $_baseId=null;
	
	public function setBaseId($baseId)
	{
		$this->_baseId=$baseId;
	}
	
	public function __construct($config=null, $baseId=null)
	{
		
		if($baseId)
		{
			$this->setBaseId($baseId);
		}
		else if(array_key_exists('base_id', $config))
		{
			$this->setBaseId($config['base_id']);
		}
		if(!$this->_dataModel)
		{
			throw new Ea_Model_Layout_Exception('You must specify a data model');
		}
	}
	
	public function getColumnAdapter($name)
	{
		$obj=$this->getMetaData($name, 'adapter', 'instance');
		if($obj) return $obj;
		$config=$this->getMetaData($name, 'adapter', 'config');
		$class=$this->getMetaData($name, 'adapter', 'class');
		if(!$class)
		{
			$class=$this->getDefaultRecordAdapterClassByType($this->getColumnType($name));
		}
		$instance=new $class($name, $this->_baseId, $this->getMetaData($name), $config);
		$this->setColumnMetaPart($name, 'adapter', 'instance', $instance);
		return $instance;
	}

}
?>