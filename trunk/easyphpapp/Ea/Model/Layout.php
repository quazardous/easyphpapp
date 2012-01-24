<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Model
 * @subpackage  Form
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Model/Abstract.php';

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
	 *   	'date'              => array('format'=>'strftime() format to read/write from base'),
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
	
	/**
	 * FIFO for class prefix.
	 * @var array
	 */
	protected $_recordAdapterPrefix=array('Ea_Model_Layout_Record_Adapter');
	protected $_defaultRecordAdapterName = 'String';
	protected $_defaultRecordAdapterNameByType=array(
		'string' => 'String',
		'enum' => 'Enum',
	  'boolean' => 'Boolean',
	);

	protected $_defaultDateFormat='%Y-%m-%d';
	protected $_defaultDatetimeFormat='%Y-%m-%d %H:%M:%S';

	public function addRecordAdapterPrefix($prefix) {
	  array_unshift($this->_recordAdapterPrefix, $prefix);
	}
	
	public function setDefaultDateFormat($format)
	{
		$this->_defaultDateFormat=$format;
	}
	
	public function setDefaultDatetimeFormat($format)
	{
		$this->_defaultDatetimeFormat=$format;
	}
	
	public function getDefaultDateFormat()
	{
		return $this->_defaultDateFormat;
	}
	
	public function getDefaultDatetimeFormat()
	{
		return $this->_defaultDatetimeFormat;
	}

	public function getDefaultDateDbformat()
	{
		if((!$this->_defaultDateDbformat)&&$this->_dataModel) return $this->_dataModel->getDefaultDateDbformat();
		return $this->_defaultDateDbformat;
	}
	
	public function getDefaultDatetimeDbformat()
	{
		if((!$this->_defaultDatetimeDbformat)&&$this->_dataModel) return $this->_dataModel->getDefaultDatetimeDbformat();
		return $this->_defaultDatetimeDbformat;
	}
	
	protected function getDefaultRecordAdapterNameByType($type, $column)
	{
		if(array_key_exists($type, $this->_defaultRecordAdapterNameByType)) return $this->_defaultRecordAdapterNameByType[$type];
		return $this->_defaultRecordAdapterName;
	}
	
	/**
	 * Return a valid adapter class for the given name.
	 * @param string $name
	 * @param string $column
	 * @return string|boolean
	 */
	protected function getAdapterClassFromName($name, $column)
	{
	  require_once 'Zend/Loader.php';
	  $prefixes = $this->_recordAdapterPrefix;
	  if ($prefix = $this->getColumnAdapterPrefix($column)) array_unshift($prefixes, $prefix);
	  foreach ($prefixes as $prefix) {
	    try {
	      $class = $prefix.'_'.ucfirst($name);
	      Zend_Loader::loadClass($class);
	    }
	    catch (Zend_Exception $e) {
	      //nothing
	    }
	    if (class_exists($class, false)) return $class;
	  }
		return false;
	}
	
	/**
	 * Layout model constructor.
	 * 
	 * @param array|mixed|string $config
	 * 		if string : xml file to load
	 * 		else constructor call Ea_Model_Data::factory() on this.
	 * 		array(
	 * 			'data_model' => $dataModel,
	 * 			'xml' => $xmlfile,
	 * 		)
	 * 		
	 * @return unknown_type
	 */
	public function __construct($config=null)
	{	
		if(is_string($config))
		{
			$config=array('xml'=>$config);
		}
		else
		{
			require_once 'Ea/Model/Data.php';
			if($model=Ea_Model_Data::factory($config))
			{
				$config=array('data_model'=>$model);
			}
		}
		if(is_array($config))
		{
			if(array_key_exists('data_model', $config))
			{
				$this->setDataModel($config['data_model']);
			}
			if(array_key_exists('xml', $config))
			{
				$this->loadFromXmlFile($config['xml']);
			}
		}
		if($this->_dataModel) $this->_analyzeDataModel();
	}
	
	protected function _analyzeDataModel()
	{
		$this->_columns=$this->_dataModel->getOrderedColumns();
		$i=count($this->_columns);
		foreach($this->_columns as $column)
		{
			$this->setColumnDisplay($column, true);
			$this->setColumnOrder($column, $i);
//			$this->setColumnLabel($column, $this->_dataModel->getColumnLabel($column));
//			$this->setColumnDateFormat($column, $this->_dataModel->getColumnDateFormat($column));
			$i++;
		}
		$this->_ordered=true;
	}

	protected function onLoadColumn($column)
	{
		$this->setColumnDisplay($column, true);
	}

	/**
	 * Return the list of columns with given type.
	 * 
	 * @param string|array(string) $type
	 * @return array
	 */
	public function getColumnsOfType($type)
	{
		if($this->_dataModel) $res=$this->_dataModel->getColumnsOfType($type);
		else $res=array();
		return array_unique(array_merge($res, parent::getColumnsOfType($type)));
	}
	
	public function setColumnAdapter($column, $adapter)
	{
		if($adapter instanceof Ea_Model_Layout_Record_Adapter_Interface)
		{
			$this->setColumnAdapterInstance($column, $adapter);
		}
		else if(is_string($adapter))
		{
			$this->setColumnAdapterClass($column, $adapter);
		}
		else
		{
			require_once 'Ea/Model/Layout/Exception.php';
			throw new Ea_Model_Layout_Exception('No valid adapter');
		}
	}
	
	/**
	 * Return an instance of Ea_Layout_Record_Adapter_Interface for the column.
	 * 
	 * @param string $column
	 * @return Ea_Layout_Record_Adapter_Interface
	 */
	public function getColumnAdapter($column)
	{
		$obj=$this->getColumnAdapterInstance($column);
		if($obj) return $obj;
		$class=$this->getColumnAdapterClass($column);
		if(!$class)
		{
			$name=$this->getColumnAdapterName($column);
			if(!$name)
			{
				$name=$this->getDefaultRecordAdapterNameByType($this->getColumnType($column), $column);
			}
			$class=$this->getAdapterClassFromName($name, $column);
		}
		if (!$class) {
		  require_once 'Ea/Model/Layout/Exception.php';
			throw new Ea_Model_Layout_Exception("No adapter class for $column");
		}
		require_once 'Zend/Loader.php';
		Zend_Loader::loadClass($class);
		$instance=new $class($column, $this);
		$this->setColumnAdapterInstance($column, $instance);
		return $instance;
	}
		
	/**
	 * Return the column header.
	 * 
	 * @param $name column
	 * @return mixed
	 */
	public function getColumnHeader($column)
	{
		// try to get some user content
		$content=$this->getColumnHeaderContent($column);
		if($content) return $content;
		$adapter=$this->getColumnHeaderAdapter($column);
		if($adapter instanceof Ea_Model_Layout_Header_Adapter_Interface)
		{
			return $adapter->getHeader($column, $this);
		}
		return $this->getColumnLabel($column);;
	}	

	public function getColumnLabel($column)
	{
		$label=$this->getMetaData($column, 'label');
		if($label) return $label;
		if($this->_dataModel) return $this->_dataModel->getColumnLabel($column);
		return $column;
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
		if($this->_dataModel)
		{
			$value=$this->_dataModel->filterRecordValue($column, $value, $data);
		}
		switch($type=$this->getColumnType($column))
		{
			case 'date': case 'datetime':
				//TODO : format data vs layout....
				if ($this->emptyDbDate($value)) return null;
				$dbformat=$this->getColumnDateDbformat($column);
				$format=$this->getColumnDateFormat($column);
				if(!$format)
				{
					if($type=='date') $format=$this->getDefaultDateFormat();
					else $format=$this->getDefaultDatetimeFormat();
				}
				if($dbformat==$format) return $value;
				if(!($dbformat&&$format)) return $value;
				$d=strptime($value, $dbformat);
				if(!$d) return $value;
				return strftime($format,
					mktime(
						$d['tm_hour'],
						$d['tm_min'],
						$d['tm_sec'],
						$d['tm_mon']+1,
						$d['tm_mday'],
						$d['tm_year']+1900));
			default: return $value;
		}
	}
		
	public function __call($name, $arguments)
	{
		if(strpos($name, 'getColumn')===0)
		{
			// if meta cannot be found in layout model try to get it from data model.
			$value=parent::__call($name, $arguments);
			if($value===null&&$this->getDataModel()) $value=call_user_func_array(array($this->getDataModel(), $name), $arguments);
			return $value;
		}
		return parent::__call($name, $arguments);
	}
	
	
}
