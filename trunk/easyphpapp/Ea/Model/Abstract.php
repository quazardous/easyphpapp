<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Model
 * @subpackage  Base
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.3.2-20081128
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

/**
 * Model class.
 * The base class to represent model.
 * 
 */
abstract class Ea_Model_Abstract
{
	const type_string      = 'string';
	const type_text        = 'text';
	const type_integer     = 'integer';
	const type_float       = 'float';
	const type_date        = 'date';
	const type_datetime    = 'datetime';
	const type_boolean     = 'boolean';
	const type_enum        = 'enum';
	const type_stream      = 'stream';
	const type_cstream     = 'cstream';
	const type_binary      = 'binary';
	const type_unknown     = 'unknown';
	
	/**
	 * Meta info on columns.
	 * array(
	 *   'column name'=>array(
	 *   	'type'          => 'string|text|integer|float|date|datetime|boolean|enum',
	 *   	'date'          => array('format'=>'strptime() format to read from base', 'outformat'=>'strftime() format to read/write from base'),
	 *      'boolean'       => array('value true'=>true, 'value false'=>false),
	 *      'number'        => array('decimals'=>2, 'dec_point'=>',', 'thousands_sep'=>' '),
	 *      'string'        => array('length'=>32), // for string or text
	 *      'enum'          => array('value'=>'option', ...),
	 *      'default'       => 'value'|array('type'=>'value|callback', 'value'=>'value', 'callback'=>'callback'),
	 *      'mandatory'		=> true|false,
	 *      'order'		    => $i, // display order
	 *      'display'		=> true|false, // display column
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
	
	public function getColumnType($name)
	{
		return $this->getMetaData($name, 'type');
	}
	
	public function setColumnType($name, $type)
	{
		$this->setColumnMeta($name, 'type', $type);
	}

	public function getColumnOrder($name)
	{
		return $this->getMetaData($name, 'order');
	}
	
	public function setColumnOrder($name, $type)
	{
		$this->_ordered=false;
		$this->setColumnMeta($name, 'order', $type);
	}

	public function setColumnDisplay($name, $display)
	{
		$this->setColumnMeta($name, 'display', $display);
	}
	
	public function getColumnDisplay($name)
	{
		return $this->getMetaData($name, 'display');
	}

	public function setColumnLabel($name, $display)
	{
		$this->setColumnMeta($name, 'label', $display);
	}
	
	public function getColumnLabel($name)
	{
		return $this->getMetaData($name, 'label');
	}
	
	/**
	 * Return the list of columns with given type.
	 * 
	 * @param string|array(string) $type
	 * @return array
	 */
	public function getColumnsOfType($type)
	{
		if(!is_array($type)) $type=array($type);
		$res=array();
		foreach($this->getColumns() as $column)
		{
			if(in_array($this->getColumnType($column),$type))
			{
				$res[]=$column;
			}
		}
		return $res;
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

	protected $_ordered=true;
	protected $_columns=array();
	
	public function getColumns()
	{
		return $this->_columns;
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
	
}