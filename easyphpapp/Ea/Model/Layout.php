<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Model
 * @subpackage  Form
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.3.0.20081113
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Model/Abstract.php';
require_once 'Ea/Model/Data.php';
require_once 'Ea/Model/Data/Abstract.php';
require_once 'Ea/Model/Layout/Exception.php';
require_once 'Zend/Loader.php';

/**
 * Layout model class.
 * This class handle the layout description for a data model.
 *
 */
class Ea_Model_Layout extends Ea_Model_Abstract
{
	
	/**
	 * Meta info on columns.
	 * array(
	 *   'column name'=>array(
	 *      'adapter'		    => array('class'=>'Ea_Layout_Record_Adapter_Interface+Ea_Model_Layout_Record_Adapter_Interface', 'config'=>$congig_for_the_adapter),
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
	
	protected $_defaultRecordAdapterClass = 'Ea_Model_Layout_Record_Adapter_Text';
	protected $_defaultRecordAdapterClassByType=array(
		self::type_string => 'Ea_Model_Layout_Record_Adapter_Text',
	);
	
	protected function getDefaultRecordAdapterClassByType($type)
	{
		if(array_key_exists($type, $this->_defaultRecordAdapterClassByType)) return $this->_defaultRecordAdapterClassByType[$type];
		return $this->_defaultRecordAdapterClass;
	}
	
	public function __construct($config=null)
	{
		if($model=Ea_Model_Data::factory($config))
		{
			$config=array('data_model'=>$model);
		}
		if(array_key_exists('data_model', $config))
		{
			$this->setDataModel($config['data_model']);
		}
		if(!$this->_dataModel)
		{
			throw new Ea_Model_Layout_Exception('You must specify a data model');
		}
		$this->_analyzeDataModel();
	}
		
	protected function _analyzeDataModel()
	{
		$this->_columns=array();
		foreach($this->_dataModel->getOrderedColumns() as $column)
		{
			$this->_metadata[$column]=$this->_dataModel->getMetaData($column);
			$this->_columns[]=$column;
		}
		$this->_ordered=true;
	}
	
	public function getColumnAdapter($name)
	{
		$obj=$this->getMetaData($name, 'adapter', 'instance');
		if($obj) return $obj;
		$class=$this->getMetaData($name, 'adapter', 'class');
		if(!$class)
		{
			$class=$this->getDefaultRecordAdapterClassByType($this->getColumnType($name));
		}
		Zend_Loader::loadClass($class);
		$instance=new $class($name, $this);
		$this->setColumnMetaPart($name, 'adapter', 'instance', $instance);
		return $instance;
	}
	
	public function getColumnHeader($name)
	{
		// try to get some user content
		$content=$this->getMetaData($name, 'header', 'content');
		if($content) return $content;
		return $this->getColumnLabel($name);;
		//TODO add some header adapter support
	}
	
	public function filterRecordValue($column, $value)
	{
		return $value;
	}
	
}
?>