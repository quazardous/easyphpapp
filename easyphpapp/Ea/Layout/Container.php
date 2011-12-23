<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Base
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Element/Abstract.php';

/**
 * Container tag element layout class.
 * In HTML : a tag that can contain sub layouts (ie. <div>...</div>).
 * 
 */
class Ea_Layout_Container extends Ea_Layout_Element_Abstract
{
  
  /**
   * Default tag is div.
   * @var string
   */
  protected $_tag='div';
  
	/**
	 * Container layout constructor.
	 * 
	 * @param string $tag
	 * @param array $config associative array of parameters
	 * 
	 * - 'callbacks' : an array of "on add layout" callbacks
	 * @see addCallback()
	 * 
	 */
	public function __construct($tag=null, $config=null)
	{
		parent::__construct($config);
		if($tag) $this->setTag($tag);
		if(is_array($config))
		{
			if(array_key_exists('callbacks', $config))
			{
				$this->setCallbacks($config['callbacks']);
			}
		}
	}
	
	/**
	 * The sublayouts array.
	 * 
	 * @var array(Ea_Layout_Abstract)
	 */
	protected $_subLayouts=array();
	
	public function resetSubLayouts()
	{
		$this->_subLayouts=array();
	}
	
	/**
	 * Add a sublayout.
	 * If $content is not a Ea_Layout_Abstract, try to create a Ea_Layout_Text with $content.
	 * add() call execCallbacksOn()
	 * @see Ea_Layout_Text
	 * @uses execCallbacksOn()
	 * 
	 * @param Ea_Layout_Abstract|mixed $content
	 * @param boolean $append
	 */
	public function add($content, $append=true)
	{
		$this->_add($content, $append);
	}
	
	/**
	 * Internal Add a sublayout.
	 * 
	 * @param Ea_Layout_Abstract|mixed $content
	 * @param boolean $append
	 */
	protected function _add($content, $append=true)
	{
		if(is_array($content)&&(!is_object($content)))
		{
			foreach($content as $item)
			{
				$this->_add($item, $append);
			}
			return;
		}
		if(!($content instanceof Ea_Layout_Abstract))
		{
			require_once 'Ea/Layout/Text.php';
			$content=new Ea_Layout_Text($content, null, null);
		}
		$this->_beforeAdd($content);
		$content->beforeAdded($this);
		$content->setParent($this);
		$this->execCallbacksOn($content);
		if($append) array_push($this->_subLayouts, $content);
		else array_unshift($this->_subLayouts, $content);
		$content->afterAdded($this);
		$this->_afterAdd($content);
	}
	
	/**
	 * Before add function.
	 * @param Ea_Layout_Abstract $layout
	 */
	protected function _beforeAdd(Ea_Layout_Abstract $layout) {
	  
	}
	
	/**
	 * After add function.
	 * @param Ea_Layout_Abstract $layout
	 */
	protected function _afterAdd(Ea_Layout_Abstract $layout) {
	   
	}
	
	/**
	 * Render the open tag.
	 * 
	 */
	protected function open()
	{
		echo "<";
		echo $this->_tag;
		$this->renderAttributes();
		echo ">";
	}
	
	/**
	 * Render the container and the sublayouts.
	 * 
	 */
	protected function render()
	{
		$this->open();
		foreach($this->_subLayouts as $layout)
		{
			$layout->display();
		}
		$this->close();
	}
	
	/**
	 * Render the close tag.
	 * 
	 */
	protected function close()
	{
		echo "</";
		echo $this->_tag;
		echo ">";
	}
	
	/**
	 * Callbacks triggered when adding sublayouts to the container.
	 * @see addCallback()
	 * 
	 * @var array(callback) $_callbacks
	 */
	protected $_callbacks=array();

	/**
	 * Add a callback.
	 * These callbacks will be triggered recursively each time a layout is added to a container or a subcontainer.
	 * The callback must take a parameter : the added layout.
	 * @see add()
	 * 
	 * @param callback $callback
	 */
	public function addCallback($callback)
	{
		array_push($this->_callbacks, $callback);
	}

	/**
	 * Add/set an array of callbacks.
	 * 
	 * @param array(callback) $callbacks
	 * @param boolean $replace add or replace existing callbacks
	 */
	public function setCallbacks(array $callbacks, $replace=false)
	{
		if($replace)
		{
			$this->_callbacks=$callbacks;
		}
		else
		{
			foreach($callbacks as $callback)
			{
				$this->addCallback($callback);
			}
		}
	}

	/**
	 * Get the callbacks array.
	 * 
	 * @return array(callback)
	 */
	public function getCallbacks()
	{
		return $this->_callbacks;
	}
	
	/**
	 * Execute the callback on the given layout.
	 * @see addCallback()
	 * 
	 * @param Ea_Layout_Abstract $layout
	 * @param boolean $applyParentCallbacks recursively call the callbacks of the parents container
	 * @param boolean $applyCallbacksOnChildren call the callbacks on the children of the new layout
	 */
	protected function execCallbacksOn(Ea_Layout_Abstract $layout, $applyParentCallbacks=true, $applyCallbacksOnChildren=true)
	{
		if($applyParentCallbacks&&$this->_parent) $this->_parent->execCallbacksOn($layout, true, $applyCallbacksOnChildren);
		foreach($this->_callbacks as $callback)
		{
			call_user_func($callback, $layout);
		}
		if($applyCallbacksOnChildren && $layout instanceof self)
		{
			foreach($layout->_subLayouts as $sublayout)
			{
				$this->execCallbacksOn($sublayout, $applyParentCallbacks, true);
			}
		}
	}
	
}