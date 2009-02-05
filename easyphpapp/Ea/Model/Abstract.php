<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Model
 * @subpackage  Base
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.3.4-20090205
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Xml/Element.php';

/**
 * Model class.
 * The base class to represent model.
 * 
 */
abstract class Ea_Model_Abstract
{
	
	/**
	 * Meta info on columns.
	 * array(
	 *   'column name'=>array(
	 *      'order'		    => $i, // display order
	 *      'label'		    => 'nice label',
	 *   ),
	 * )
	 * 
	 * @var array
	 */
	protected $_metadata=array();
	
	public function	getMetaData($name, $param=null, $part=null)
	{
		if(!is_array($this->_metadata)) return null;
		if(!array_key_exists($name, $this->_metadata)) return null;
		if($param===null) return $this->_metadata[$name];
		if(!is_array($this->_metadata[$name])) return null;
		if(!array_key_exists($param, $this->_metadata[$name])) return null;
		if($part===null) return $this->_metadata[$name][$param];
		if(!is_array($this->_metadata[$name][$param])) return null;
		if(!array_key_exists($part, $this->_metadata[$name][$param])) return null;
		return $this->_metadata[$name][$param][$part];
	}
	
	public function getColumnOrder($name)
	{
		return $this->getMetaData($name, 'order');
	}
	
	public function setColumnOrder($name, $index)
	{
		$this->_ordered=false;
		$this->setColumnMeta($name, 'order', $index);
	}

	public function setColumnLabel($name, $display)
	{
		$this->setColumnMeta($name, 'label', $display);
	}
	
	public function getColumnLabel($name)
	{
		return $this->getMetaData($name, 'label');
	}

	protected $_ordered=true;
	protected $_columns=array();
	
	public function getColumns()
	{
		return $this->_columns;
	}

	/**
	 * Reset the list of columns.
	 */
	public function resetColumns()
	{
		$this->_columns=array();
	} 
	
	public function getOrderedColumns()
	{
		if(!$this->_ordered)
		{
			$tmp=array();
			$n=count($this->_columns);
			foreach($this->_columns as $i => $column)
			{
				$order=$this->getColumnOrder($column);
				if($order===null) $order=$n;
				$tmp[$column]=$order+$i*1.0/$n;
			}
			asort($tmp);
			$this->_columns=array_keys($tmp);
			$this->_ordered=true;
		}
		return $this->_columns;
	}
	
	/**
	 * Set meta data.
	 * 
	 * @param string|array(string) $name column or list of columns, '*' for all columns
	 * @param $param
	 * @param $value
	 */
	public function setColumnMeta($columns, $param, $value)
	{
		if(!is_array($this->_metadata)) $this->_metadata=array();
		if($columns=='*') $columns=$this->getColumns();
		else if(!is_array($columns)) $columns=array($columns);
		foreach($columns as $name)
		{
			if(!array_key_exists($name, $this->_metadata)) $this->_metadata[$name]=array();
			if($value===null) unset($this->_metadata[$name][$param]);
			else $this->_metadata[$name][$param]=$value;
		}
	}

	/**
	 * Set part of meta data.
	 * 
	 * @param string|array(string) $name column or list of columns, '*' for all columns
	 * @param $param
	 * @param $part
	 * @param $value
	 */
	public function setColumnMetaPart($columns, $param, $part, $value)
	{
		if(!is_array($this->_metadata)) $this->_metadata=array();
		if($columns=='*') $columns=$this->getColumns();
		else if(!is_array($columns)) $columns=array($columns);
		foreach($columns as $name)
		{
			if(!array_key_exists($name, $this->_metadata)) $this->_metadata[$name]=array();
			if(!array_key_exists($param, $this->_metadata[$name])) $this->_metadata[$name][$param]=array();
			if($value===null) unset($this->_metadata[$name][$param][$part]);
			else $this->_metadata[$name][$param][$part]=$value;
		}
	}
	
	/**
	 * Load model definition from xml file.
	 * 
	 * @param $filename
	 */
	public function loadFromXmlFile($filename)
	{
		$this->loadFromXml(Ea_Xml_Element::load_file($filename));
	}
	
	protected function loadFromXml(Ea_Xml_Element $xml)
	{
		if(isset($xml->common))
		{
			if(isset($xml->common->default))
			{
				foreach($xml->common->default as $xml_default)
				{
					$this->setDefaultFromXml($xml_default);
				}
			}
		}
		$xml_columns=$xml->columns;
		$i=count($this->_columns);
		foreach($xml_columns->column as $xml_column)
		{
			$column=$xml_column['name']->toString();
			if(!in_array($column, $this->_columns))
			{
				$this->_columns[]=$column;
				$this->setColumnOrder($column, $i);
				$this->setColumnLabel($column, $column);
				$this->onLoadNewColumn($column);
				$i++;				
			}
			$this->onLoadColumn($column);
			$this->loadColumnMetaFromXml($column, $xml_column);
		}
	}
	
	protected function onLoadNewColumn($column)
	{
	}

	protected function onLoadColumn($column)
	{

	}

	protected function loadColumnMetaFromXml($column, Ea_Xml_Element $xml_column)
	{
		foreach($xml_column->metadata as $xml_metadata)
		{
			$this->setColumnMetaFromXml($column, $xml_metadata);
		}
	}
	
	protected function setDefaultFromXml(Ea_Xml_Element $xml_default)
	{
		$words=explode('/',strtolower($xml_default['name']->toString()));
		$f='setDefault';
		foreach($words as $word)
		{
			$f.=ucfirst($word);
		}
		if(method_exists($this, $f))
		{
			$this->$f($this->readXmlValue($xml_default));
			return true;
		}
		return false;
	}
	
	
	protected function setColumnMetaFromXml($column, Ea_Xml_Element $xml_metadata)
	{
		$words=explode('/',strtolower($xml_metadata['name']->toString()));
		$f='setColumn';
		foreach($words as $word)
		{
			$f.=ucfirst($word);
		}
		if(method_exists($this, $f))
		{
			$this->$f($column, $this->readXmlValue($xml_metadata));
			return true;
		}
		return false;
	}
	
	protected function readXmlValue(Ea_Xml_Element $xml_value)
	{
		if(isset($xml_value->array))
		{
			$arr=array();
			if(isset($xml_value->array->item))
			{
				foreach($xml_value->array->item as $xml_item)
				{
					$value=$this->readXmlValue($xml_item);
					if(isset($xml_item['index'])) $arr[$xml_item['index']->toString()]=$value;
					else $arr[]=$value;
				}
			}
			return $arr;
		}
		return $xml_value->toString();
	}
}