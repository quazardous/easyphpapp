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
	
}