<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Model
 * @subpackage  Form
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.3.4-20090204
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
	 *      'adapter'		    => array('name'=>'name to add to prefix', 'config'=>$congig_for_the_adapter),
	 *      'header'		    => array('content'=>$content, 'adapter'=>'Ea_Layout_Record_Adapter_Interface'),
	 *      'order'		        => $i, // display order
	 *      'display'	    	=> true|false, // display column
	 *      'label'		        => 'nice label',
	 *   	'date'              => array('outformat'=>'strftime() format to read/write from base'),
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
	
	/**
	 * Get data model.
	 * 
	 * @var Ea_Model_Data_Abstract
	 */
	public function getDataModel()
	{
		return $this->_dataModel;
	}
	
	protected $_recordAdapterPrefix='Ea_Model_Layout_Record_Adapter';
	protected $_defaultRecordAdapterName = 'String';
	protected $_defaultRecordAdapterNameByType=array(
		'string' => 'String',
	);
	
	protected function getDefaultRecordAdapterNameByType($type)
	{
		if(array_key_exists($type, $this->_defaultRecordAdapterNameByType)) return $this->_defaultRecordAdapterNameByType[$type];
		return $this->_defaultRecordAdapterName;
	}
	
	protected function getAdapterClassFromName($name)
	{
		return $this->_recordAdapterPrefix.'_'.ucfirst($name);
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
		$this->_columns=$this->_dataModel->getOrderedColumns();
		$i=0;
		foreach($this->_columns as $column)
		{
			$this->setColumnDisplay($column, true);
			$this->setColumnOrder($column, $i);
			$this->setColumnLabel($column, $this->_dataModel->getColumnLabel($column));
			$this->setColumnDateFormat($column, $this->_dataModel->getColumnDateOutformat($column));
			$i++;
		}
		$this->_ordered=true;
	}

	protected function onLoadColumn($column)
	{
		$this->setColumnDisplay($column, true);
	}
	
	public function getColumnType($name)
	{
		return $this->_dataModel->getColumnType($name);
	}

	/**
	 * Return the list of columns with given type.
	 * 
	 * @param string|array(string) $type
	 * @return array
	 */
	public function getColumnsOfType($type)
	{
		return $this->_dataModel->getColumnsOfType($type);
	}

	public function setColumnAdapterInstance($name, $instance)
	{
		$this->setColumnMetaPart($name, 'adapter', 'instance', $instance);
	}

	public function getColumnAdapterInstance($name)
	{
		return $this->getMetaData($name, 'adapter', 'instance');
	}
	
	public function setColumnAdapterName($name, $class)
	{
		$this->setColumnMetaPart($name, 'adapter', 'name', $class);
	}

	public function getColumnAdapterName($name)
	{
		return $this->getMetaData($name, 'adapter', 'name');
	}

	public function setColumnAdapterConfig($name, $config)
	{
		$this->setColumnMetaPart($name, 'adapter', 'config', $config);
	}

	public function getColumnAdapterConfig($name)
	{
		return $this->getMetaData($name, 'adapter', 'config');
	}
	
	public function setColumnHeaderAdapter($name, $adapter)
	{
		$this->setColumnMetaPart($name, 'header', 'adapter', $adapter);
	}

	public function getColumnHeaderAdapter($name)
	{
		return $this->getMetaData($name, 'header', 'adapter');
	}
	
	public function setColumnHeaderContent($name, $content)
	{
		$this->setColumnMetaPart($name, 'header', 'content', $content);
	}

	public function getColumnHeaderContent($name)
	{
		return $this->getMetaData($name, 'header', 'content');
	}
	
	public function setColumnAdapter($name, $adapter)
	{
		if($adapter instanceof Ea_Model_Layout_Record_Adapter_Interface)
		{
			$this->setColumnAdapterInstance($name, $adapter);
		}
		else if(is_string($adapter))
		{
			$this->setColumnAdapterClass($name, $adapter);
		}
		else
		{
			throw new Ea_Model_Layout_Exception('No valid adapter');
		}
	}
	
	/**
	 * Return an instance of Ea_Layout_Record_Adapter_Interface for the column.
	 * 
	 * @param $name column
	 * @return Ea_Layout_Record_Adapter_Interface
	 */
	public function getColumnAdapter($column)
	{
		$obj=$this->getColumnAdapterInstance($column);
		if($obj) return $obj;
		$name=$this->getColumnAdapterName($column);
		if(!$name)
		{
			$name=$this->getDefaultRecordAdapterNameByType($this->getColumnType($column));
		}
		$class=$this->getAdapterClassFromName($name);
		Zend_Loader::loadClass($class);
		$instance=new $class($column, $this);
		$this->setColumnAdapterInstance($column, $instance);
		return $instance;
	}
	
	public function setColumnDisplay($name, $display)
	{
		$this->setColumnMeta($name, 'display', $display);
	}
	
	public function getColumnDisplay($name)
	{
		return $this->getMetaData($name, 'display');
	}

	/**
	 * Set the output date format.
	 * The db date format is set in the data model.
	 * 
	 * @param $name
	 * @param $format
	 */
	public function setColumnDateFormat($name, $format)
	{
		$this->setColumnMetaPart($name, 'date', 'outformat', $format);
	}
	
	public function getColumnDateFormat($name)
	{
		return $this->getMetaData($name, 'date', 'outformat');
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
		$value=$this->_dataModel->filterRecordValue($column, $value);
		switch($this->getColumnType($column))
		{
		case 'date': case 'datetime':
				if(!$value)return $value;
				$format=$this->_dataModel->getMetaData($column, 'date', 'format');
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
			default: return $value;
		}
	}
	
}
?>