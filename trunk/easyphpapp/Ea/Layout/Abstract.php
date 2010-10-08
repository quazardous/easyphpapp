<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Base
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.4.6-20101007 [$Id$]
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

/**
 * Abstract layout class.
 *
 * A layout is all thing that can be put on a page an rendered.
 * 
 */
abstract class Ea_Layout_Abstract
{

	
	/**
	 * Abstract layout constructor.
	 *
	 * @param array|Ea_Page|Ea_Page_Interface $config
	 * 
	 *  - if associative array : config params, can be
	 * 
	 *  -- page : the page to which the layout belongs
	 * 
	 *  - if Ea_Page : the page to which the layout belongs
	 * 
	 * You can use your own Page class  even outside EasyPhpApp engine (like Zend_View).
	 * @see Ea_Page_Interface
	 * @see setPage()
	 */
	public function __construct($config=null)
	{
		if(!$config)
		{
			$config=array();
		}
		else if($config instanceof Ea_Page_Interface)
		{
			$config=array('page'=>$config);
		}
		if(is_array($config))
		{
			if(array_key_exists('page', $config))
			{
				$this->setPage($config['page']);
			}
		}
		$this->executeRegisterCallbacks();
	}

	/**
	 * Parent container.
	 *
	 * @var Ea_Layout_Container
	 */
	protected $_parent=null;
		
	/**
	 * Set the parent container.
	 * 
	 * @param Ea_Layout_Container $parent
	 */
	public function setParent(Ea_Layout_Container $parent)
	{
		$this->_parent=$parent;
		$this->setPage($parent->getPage());
	}

	/**
	 * Return parent container
	 *
	 * @return Ea_Layout_Container
	 */
	public function getParent()
	{
		return $this->_parent;
	}
	
	/**
	 * The page to which the layout belongs.
	 *
	 * @var Ea_Page
	 */
	protected $_page=null;

	/**
	 * Callbacks triggered in constructor.
	 * Callbacks take new object ($this) at unique argument.
	 * 
	 * @var array
	 */
	static protected $_registerCallbacks=array();
		
	/**
	 * Add register callback.
	 * 
	 * @param callback $callback
	 */
	static public function addRegisterCallback($callback)
	{
		self::$_registerCallbacks[]=$callback;	
	}
	
	protected function executeRegisterCallbacks()
	{
		foreach(self::$_registerCallbacks as $callback)
		{
			call_user_func($callback, $this);
		}
	}
	
	/**
	 * Set the page.
	 * You can use your own Page class even outside EasyPhpApp engine (like Zend_View).
	 * Your page class must "mimic"(*) Ea_Page_Interface.
	 * Some routing functionalities will be restricted.
	 * (*) If you use $layout=new Ea_Layout_Abstract($mypage); $mypage will not be recognized if it doesn't implement Ea_Page_Interface.
	 * But if you use $layout->setPage($mypage), your class just have to "mimic" Ea_Page_Interface.
	 * @see Ea_Page_Interface
	 * @see __construct()
	 *
	 * @param Ea_Page $page
	 * @see $_page
	 */
	public function setPage($page)
	{
		$this->_page=$page;
	}
	
	/**
	 * Return the page.
	 *
	 * @return Ea_Page
	 * @see $_page
	 */
	public function getPage()
	{
		if($this->_page) return $this->_page;
		if($this->getParent()) $this->_page=$this->getParent()->getPage(); 
		return $this->_page;
	}
		
	/**
	 * Escape methode inherited from Ea_Page.
	 * 
	 * Can be used to protect string at render.
	 *
	 * @param string $string
	 * @return string
	 *
	 * @see Ea_Page
	 */
	public function escape($string)
	{
		return $this->getPage()->escape($string);
	}

	/*
	public function encode($string)
	{
		// no need for encode in layout context. see Ea_Page
	}
	*/
	
	/**
	 * Pre render validation stuff.
	 * It's highly recommended that preRender() call parent::preRender().
	 * @return boolean if not true do not render the layout
	 */
	protected function preRender()
	{
		return true;
	}
	
	/**
	 * Abstract render method.
	 * 
	 * Derived class must overload this method in order to render the layout.
	 * 
	 * It's highly recommended that abstract implements only render stuff.
	 * Special stuff like validaion shoud be put in preRender().
	 * So it's highly recommended that render() call preRender().
	 * 
	 * @see preRender()
	 * 
	 */
	abstract protected function render();
	
	/**
	 * Post render completion stuff.
	 * It's highly recommended that postRender() call parent::postRender().
	 */
	protected function postRender()
	{
		
	}
	
	public function __sleep()
    {
        return array();
    }
	
    /**
     * Render and get HTML content as string.
     * 
     * @return string
     */
    public function getContent()
    {
    	if($this->preRender())
    	{
    		ob_start();
    		$this->render();
    		$content=ob_get_clean();
    		$this->postRender();
    		return $content;
    	}
    	return '';
    }
    
    /**
     * Render and dispaly content.
     * 
     */
    public function display()
    {
    	if($this->preRender())
    	{
    		$this->render();
    		$this->postRender();
    		return true;
    	}
    	return false;
    }
    
    /**
     * Method called just before adding the layout to it's container.
     * 
     * @param Ea_Layout_Container $parent
     */
    protected function beforeAdd(Ea_Layout_Container $parent)
    {
    	
    }

    /**
     * Method called just after adding the layout to it's container.
     * 
     * @param Ea_Layout_Container $parent
     */
    protected function afterAdd(Ea_Layout_Container $parent)
    {
    	
    }
    
}
