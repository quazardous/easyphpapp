<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Model
 * @subpackage  Base
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.3.6-20090223
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
	
	public function	getMetaData($column, $param=null, $part=null)
	{
		if(!is_array($this->_metadata)) return null;
		if(!array_key_exists($column, $this->_metadata)) return null;
		if($param===null) return $this->_metadata[$column];
		if(!is_array($this->_metadata[$column])) return null;
		if(!array_key_exists($param, $this->_metadata[$column])) return null;
		if($part===null) return $this->_metadata[$column][$param];
		if(!is_array($this->_metadata[$column][$param])) return null;
		if(!array_key_exists($part, $this->_metadata[$column][$param])) return null;
		return $this->_metadata[$column][$param][$part];
	}
		
	public function setColumnOrder($name, $index)
	{
		$this->_ordered=false;
		$this->setColumnMeta($name, 'order', $index);
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
		if(isset($xml->common->param))
		{
			foreach($xml->common->param as $xml_param)
			{
				$this->setParamFromXml($xml_param);
			}
		}
		if(isset($xml->columns->column))
		{
			$i=count($this->_columns);
			foreach($xml->columns->column as $xml_column)
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

	static protected function get_function_name_from_name($prefix, $name)
	{
		$words=explode('/',eregi_replace('[^0-9a-z_]','/',strtolower($name)));
		foreach($words as $word)
		{
			if($word)$prefix.=ucfirst($word);
		}
		return $prefix;
	}

	static protected function get_id_from_name($name)
	{
		$words=explode('/',eregi_replace('[^0-9a-z_]','/',strtolower($name)));
		$id=array();
		foreach($words as $word)
		{
			if($word)$id[]=strtolower($word);
		}
		return $id;
	}

	static public function array_get_from_id(&$array, $id)
	{
		$arr=&$array;
		$i=0;
		foreach($id as $pid)
		{
			// can't be null part of id
			if($pid===null) return null;
			if(!isset($arr[$pid])) return null;
			if(($i+1)==count($id)) return $arr[$pid];
			$arr=&$arr[$pid];
			$i++;
		}
	}

	static public function array_set_from_id(&$array, $id, $value)
	{
		$arr=&$array;
		$i=0;
		foreach($id as $pid)
		{
			// can't be null part of id
			if(($i+1)==count($id))
			{
				if($pid===null)$arr[]=$value;
				else $arr[$pid]=$value;
				return;
			}
			if($pid===null) throw new Exception('incorrect input id');
			if(!isset($arr[$pid]))
			{
				$arr[$pid]=array();
			}
			$arr=&$arr[$pid];
			$i++;
		}
	}
	
	/**
	 * Set column meta from meta id (from xml).
	 * 
	 * @param $id id for meta
	 * @param string|array(string) $columns column or list of columns, '*' for all columns
	 * @param $value
	 */
	protected function setColumnMetaFromId($id, $columns, $value)
	{
		if($columns=='*') $columns=$this->getColumns();
		else if(!is_array($columns)) $columns=array($columns);
		if(!is_array($this->_metadata)) $this->_metadata=array();
		foreach($columns as $column)
		{
			if(!isset($this->_metadata[$column])) $this->_metadata[$column]=array();
			self::array_set_from_id($this->_metadata[$column], $id, $value);
		}
	}

	protected function getColumnMetaFromId($id, $column)
	{
		if(!isset($this->_metadata[$column])) return null;
		return self::array_get_from_id($this->_metadata[$column], $id);
	}
	
	protected function setParamFromXml(Ea_Xml_Element $xml_param)
	{
		$f=self::get_function_name_from_name('set', $xml_param['name']->toString());
		if(method_exists($this, $f))
		{
			$this->$f($this->readXmlValue($xml_param));
			return true;
		}
		return false;
	}
	
	protected function setColumnMetaFromXml($column, Ea_Xml_Element $xml_metadata)
	{
		$f=self::get_function_name_from_name('setColumn', $xml_metadata['name']->toString());
		$this->$f($column, $this->readXmlValue($xml_metadata));
/*		if(method_exists($this, $f))
		{
			// special set
			$this->$f($column, $value);
		}
		else
		{
			// basic set
			$id=self::get_id_from_name('setColumn', $xml_metadata['name']->toString());
			$this->setColumnMetaFromId($id, $value);
		}
*/	}
	
	protected static function get_id_from_function_name($name)
	{
		$id=array();
		$i=0;
		$l=strlen($name);
		$word=false;
		while(true)
		{
			$addWord=false;
			if($i>=$l)
			{
				$addWord=true;
			}
			else if(ord($name{$i})>=ord('A')&&ord($name{$i})<=ord('Z'))
			{
				$addWord=true;
			}
			if($addWord)
			{
				if($word)
				{
					$id[]=strtolower($word);
					$word='';
				}
			}
			if($i>=$l) break;
			$word.=$name{$i};
			$i++;
		}
		return $id;
	}
		
	public function __call($name, $arguments)
	{
		if(strpos($name, 'setColumn')===0)
		{
			$id=self::get_id_from_function_name(substr($name, strlen('setColumn')));
			$this->setColumnMetaFromId($id, $arguments[0], $arguments[1]);
			return;
		}
		if(strpos($name, 'getColumn')===0)
		{
			
			$id=self::get_id_from_function_name(substr($name, strlen('getColumn')));
			return $this->getColumnMetaFromId($id, $arguments[0]);
		}
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