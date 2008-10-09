<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Form
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.1
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Container.php';
require_once 'Ea/Layout/Input/Array/Exception.php';
require_once 'Ea/Layout/Input/Abstract.php';

/**
 * Generic array of inputs or subarrays.
 * 
 * Used to facilitate input objects access througth form in a $_POST array like way.
 * 
 * @uses Ea_Layout_Input_Array_Exception
 */
class Ea_Layout_Input_Array extends Ea_Layout_Container implements ArrayAccess, Iterator, Countable
{
	protected $_items=array();
	public function offsetExists($offset)
	{
		return array_key_exists($offset, $this->_items);
	}
	
 	public function offsetGet($offset)
 	{
 		if(!array_key_exists($offset, $this->_items))
 		{
 			throw new Ea_Layout_Input_Array_Exception("'$offset' offset does not exist");
 		}
 		return $this->_items[$offset];
 	}
 	
 	protected function protectedOffsetSet($offset, $value)
 	{
 		if($offset===null)
 		{
 			$n=count($this->_items);
 			array_push($this->_items, $value);
 			end($this->_items);
 			return key($this->_items);
 		}
 		$this->_items[$offset]=$value;
 		return $offset;		
 	}
 	
 	public function offsetSet($offset, $value)
 	{
 		if(!array_key_exists($offset, $this->_items))
 		{
 			throw new Ea_Layout_Input_Array_Exception("'$offset' offset does not exist - direct assignation is not allowed");
 		}
		if(!($this->_items[$offset] instanceof Ea_Layout_Input_Abstract))
		{
			throw new Ea_Layout_Input_Array_Exception("'$offset' offset is not an Ea_Layout_Input_Abstract");
		}
		$this->_items[$offset]->setValue($value);
 	}
 	
 	public function offsetUnset($offset)
 	{
 		unset($this->_items[$offset]);
 	}
 	
 	public function current()
 	{
 		return current($this->_items);
  	}
 	
 	public function key()
 	{
 		return key($this->_items);
 	}
 	
 	public function next()
 	{
 		return next($this->_items);
 	}
 	
 	public function rewind()
 	{
 		return reset($this->_items);
 	}
 	
 	public function valid()
 	{
 		return $this->current()!==false;
 	}
 	
 	public function count()
 	{
 		return count($this->_items);
 	}
 	
 	public function recusiveWalk($callback)
 	{
 		foreach($this->_items as $item)
 		{
 			if($item instanceof self)
 			{
 				$item->recursiveWalk($callback);
 			}
 			else
 			{
 				call_user_func($callback, $item);
 			}
 		}
 	}
}

?>