<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Model
 * @subpackage  Form
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.3.2-20081128
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Model/Abstract.php';
require_once 'Ea/Model/Data.php';
require_once 'Ea/Model/Data/Abstract.php';
require_once 'Ea/Model/Layout/Exception.php';
require_once 'Zend/Loader.php';
require_once 'Ea/Model/Layout/Header/Adapter/Interface.php';

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
	 *      'header'		    => array('content'=>$content, 'adapter'=>'Ea_Layout_Record_Adapter_Interface'),
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
	
	protected $_defaultRecordAdapterClass = 'Ea_Model_Layout_Record_Adapter_String';
	protected $_defaultRecordAdapterClassByType=array(
		self::type_string => 'Ea_Model_Layout_Record_Adapter_String',
	);
	
	protected function getDefaultRecordAdapterClassByType($type)
	{
		if(array_key_exists($type, $this->_defaultRecordAdapterClassByType)) return $this->_defaultRecordAdapterClassByType[$type];
		return $this->_defaultRecordAdapterClass;
	}
	
	/**
	 * Layout model constructor.
	 * 
	 * @param array|mixed $config constructor call Ea_Model_Data::factory() on this.
	 * 		array(
	 * 			'data_model' => $dataModel,
	 * 		)
	 * @return unknown_type
	 */
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
	
	/**
	 * Return an instance of Ea_Layout_Record_Adapter_Interface for the column.
	 * 
	 * @param $name column
	 * @return Ea_Layout_Record_Adapter_Interface
	 */
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
	
	/**
	 * Return the column header.
	 * 
	 * @param $name column
	 * @return mixed
	 */
	public function getColumnHeader($name)
	{
		// try to get some user content
		$content=$this->getMetaData($name, 'header', 'content');
		if($content) return $content;
		$adapter=$this->getMetaData($name, 'header', 'adapter');
		if($adapter instanceof Ea_Model_Layout_Header_Adapter_Interface)
		{
			return $adapter->getHeader($name, $this);
		}
		return $this->getColumnLabel($name);;
		//TODO add some header adapter support
	}
	
	/**
	 * Apply special filter on record values.
	 * 
	 * @param string $column
	 * @param string $value
	 * @return string
	 * 
	 * @see Ea_Layout_Record_Adapter_Field::getValue()
	 */
	public function filterRecordValue($column, $value)
	{
		$filter=$this->getMetaData($column, 'filter');
		if($filter)
		{
			return call_user_func($filter, $value);
		}
		else
		{
			switch($this->getColumnType($column))
			{
				case self::type_date: case self::type_datetime:
					if(!$value)return $value;
					$format=$this->getMetaData($column, 'date', 'format');
					$outformat=$this->getMetaData($column, 'date', 'outformat');
					if($format==$outformat) return $value;
					if(!($format&&$outformat)) return $value;
					$d=strptime($value, $format);
					if(!$d) return $value;
					return strftime($outformat,
						mktime(
							$d['tm_hour'],
							$d['tm_min'],
							$d['tm_sec'],
							$d['tm_mon']+1,
							$d['tm_mday'],
							$d['tm_year']));
				case self::type_cstream:
					if($value)return stream_get_contents($value);
					return $value;
				default: return $value;
			}
		}
	}
	
}
?>