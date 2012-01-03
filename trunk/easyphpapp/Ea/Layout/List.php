<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  List
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Container.php';

/**
 * List layout class.
 * This class let you create HTML ul/li element.
 * 
 */
class Ea_Layout_List extends Ea_Layout_Container
{
	protected $_tag='ul';

	protected $_itemClass = 'Ea_Layout_List_Item';
	
	protected $_rootList = true;
	
	public function rootList($root = null) {
	  if ($root !== null) {
	    $this->_rootList = $root;
	  }
	  return $this->_rootList;
	}
	
	/**
	 * List layout constructor.
	 * 
	 * @param array $config associative array of parameters
	 *  
	 */
	public function __construct($config=null)
	{
		parent::__construct(null, $config);
	}
	
	protected function _beforeAdd(Ea_Layout_Abstract $layout) {
		if(!($layout instanceof Ea_Layout_List_Item))
		{
			require_once 'Ea/Layout/List/Exception.php';
			throw new Ea_Layout_List_Exception("not an instance of Ea_Layout_List_Item");
		}
	}
	
	/**
	 * Shortcut method to add an item with content.
	 * 
	 * @param Ea_Layout_Abstract|string|array $content
	 * @param array $config config for the item constructor
	 * @param boolean $append
	 * @param string $class item class
	 * 
	 * @return Ea_Layout_List_Item the new list item
	 */
	public function addItem($content=null, $config=null, $append=true, $class=null)
	{
	  if (!$class) $class = $this->_itemClass;
		Zend_Loader::loadClass($class);
		$item=new $class($config);
		if(!$item instanceof Ea_Layout_List_Item)
		{
			require_once 'Ea/Layout/List/Exception.php';
			throw new Ea_Layout_List_Exception("$class not an instance of Ea_Layout_List_Item");
		}
		$this->add($item, $append);
		if($content) $item->add($content);
		return $item;
	}
	
	/**
	* Shortcut method to add items from contents array.
	*
	* @param array $contents
	* @param array $config config for the item constructor
	* @param boolean $append
	* @param string $class item class
	*
	* @return Ea_Layout_List_Item the new list item
	*/
	public function addItems(array $contents, $config=null, $append=true, $class=null)
	{
	  $items = array();
	  foreach ($contents as $key => $content) {
	    $items[$key] = $this->addItem($content, $config, $append, $class);
	  }
	  return $items;
	}
	
	public function afterAdded(Ea_Layout_Container $parent) {
	  parent::afterAdded($parent);
  	
  	if ($parent instanceof Ea_Layout_List_Item) {
  	  $this->rootList(false);
  	  $parent->hasChildren(true);
  	}
	}
}
