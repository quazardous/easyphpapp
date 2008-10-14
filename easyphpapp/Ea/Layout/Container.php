<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Base
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.1
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Element/Abstract.php';
require_once 'Ea/Layout/Text.php';
require_once 'Ea/Layout/Abstract.php';

/**
 * Container tag element layout class.
 * In HTML : a tag that can contain sub layouts (ie. <div>...</div>).
 * 
 */
class Ea_Layout_Container extends Ea_Layout_Element_Abstract
{
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
	
	/**
	 * Add a sublayout.
	 * If $content is not a Ea_Layout_Abstract, try to create a Ea_Layout_Text with $content.
	 * add() call execCallbacksOn()
	 * @see Ea_Layout_Text
	 * @uses execCallbacksOn()
	 * 
	 * @param Ea_Layout_Abstract|mixed $content
	 */
	public function add($content, $append=true)
	{
		if(!($content instanceof Ea_Layout_Abstract))
		{
			$content=new Ea_Layout_Text(null, null, $content);
		}
		$content->setParent($this);
		$this->execCallbacksOn($content);
		if($append) array_push($this->_subLayouts, $content);
		else array_unshift($this->_subLayouts, $content);
	}
	
	/**
	 * Render the open tag.
	 * 
	 */
	protected function open()
	{
		echo "\n<";
		echo $this->_tag;
		$this->renderAttributes();
		echo ">";
	}
	
	/**
	 * Render the container and the sublayouts.
	 * 
	 */
	public function render()
	{
		$this->open();
		foreach($this->_subLayouts as $layout)
		{
			$layout->preRender();
			$layout->render();
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
	 * @param boolean $recursive recursively call the callbacks of the parents container
	 */
	protected function execCallbacksOn(Ea_Layout_Abstract $layout, $recursive=true)
	{
		if($recursive&&$this->_parent) $this->_parent->execCallbacksOn($layout, true);
		foreach($this->_callbacks as $callback)
		{
			call_user_func($callback, $layout);
		}
	}
}
?>