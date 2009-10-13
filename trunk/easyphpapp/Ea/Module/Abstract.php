<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Application
 * @subpackage  Module
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.3.4-20090202
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Module/Exception.php';

/**
 * Abstract module class.
 * 
 * You can put all the procedural stuff of a page in the module. Module can have many action method that you can trigger with route.
 * @see Ea_App
 * 
 * @uses Ea_Module_Exception
 * 
 * @abstract
 */
abstract class Ea_Module_Abstract
{
	/**
	 * The application that triggered the module.
	 *
	 * @var Ea_App
	 */
	protected $_app=null;

	/**
	 * The page that will render the module.
	 *
	 * @var Ea_Page
	 */
	protected $_page=null;

	/**
	 * Must the module automatically render the page (default).
	 * 
	 * @var boolean
	 */
	protected $_renderPage=true;

	/**
	 * Name of the module.
	 * 
	 * @var string
	 */
	protected $_name=null;

	/**
	 * Set the name.
	 * @see $_pageClass
	 * 
	 * @param string $class
	 */
	public function setName($class)
	{
		$this->_name=$class;
	}
	
	/**
	 * Get the name.
	 * 
	 * @return string
	 */
	public function getName()
	{
		return $this->_name;
	}
	
	/**
	 * If no page instance given, the module will instanciate a page object using this class name.
	 * 
	 * @var string
	 */
	protected $_pageClass='Ea_Page';

	/**
	 * Set the page class name.
	 * @see $_pageClass
	 * 
	 * @param string $class
	 */
	public function setPageClass($class)
	{
		$this->_pageClass=$class;
	}
	
	/**
	 * Get the page class name.
	 * 
	 * @return string
	 */
	public function getPageClass()
	{
		return $this->_pageClass;
	}
	
	/**
	 * Module constructor.
	 * 
	 * @param array(string=>mixed)|Ea_App $config
	 * 
	 * - if associative array : 'application' (instance of application), 'page_class', 'page' (instance of page), 'name' (module name)
	 * 
	 * - if Ea_App : instance of roouter
	 */
	public function __construct($config)
	{
		if($config instanceof Ea_App)
		{
			$config=array('application'=>$config);
		}
		if(array_key_exists('name', $config)) $this->setName($config['name']);
		if(array_key_exists('application', $config)) $this->setApp($config['application']);
		if(array_key_exists('page_class', $config)) $this->setPageClass($config['page_class']);
		if(array_key_exists('page', $config)) $this->setPage($config['page']);
	}
	
	/**
	 * Return the application
	 * @see $_app
	 *
	 * @return Ea_App
	 */
	public function getApp()
	{
		return $this->_app;
	}
	
	/**
	 * Return the application
	 * @see $_app
	 *
	 * @return Ea_App
	 * @deprecated
	 */
	public function getRouter()
	{
		return $this->getApp();
	}
	

	/**
	 * Return the application.
	 * 
	 * @param Ea_App $app
	 */
	public function setApp(Ea_App $app)
	{
		$this->_app=$app;
	}
	
	/**
	 * Used to determine if init() was already called.
	 * 
	 * @var boolean
	 */
	protected $_init=false;
	
	/**
	 * Before action trigger.
	 * Overload this method to add common init stuff.
	 * init() is called before the action method.
	 */
	public function init()
	{
		
	}
	
	/**
	 * After action trigger
	 * Overload this method to add common complete stuff.
	 * complete is called before render() each time an action is triggered.
	 * 
	 */
	public function complete()
	{
		
	}
	
	/**
	 * Set if module must render page.
	 * @see $_renderPage;
	 * 
	 * @param boolean $render
	 */
	public function setRenderPage($render)
	{
		$this->_renderPage=$render;
	}
	
	/**
	 * Render the module (call the method render() of the page).
	 * @uses $_renderPage
	 * @uses $_page
	 */
	public function render()
	{
		if($this->_renderPage) $this->getPage()->render();
	}
	
	/**
	 * Return the page. If no page instance, instanciate it.
	 * @see $_page
	 * @see $_pageClass
	 *
	 * @return Ea_Page
	 */
	public function getPage()
	{
		if(!$this->_page)
		{
			$c=$this->getPageClass();
			$this->setPage(new $c($this));
		}
		return $this->_page;
	}
	
	/**
	 * @param Ea_Page $page
	 */
	public function setPage(Ea_Page $page)
	{
		$this->_page=$page;
	}
	
	/**
	 * Set a new register. Registers are application scope variables.
	 * They can be store in session.
	 * @uses getApp()
	 * 
	 * @param mixed $name
	 * @param $value
	 * @param $store use session to store this param
	 */
	public function setRegister($name, $value, $store=false)
	{
		if($this->getApp()) $this->getApp()->setRegister($name, $value, $store);
	}
	
	public function __set($name, $value)
	{
		$this->setRegister($name, $value);
	}
	
	/**
	 * @param $name
	 * @return mixed
	 */
	public function getRegister($name)
	{
		if($this->getApp()) return $this->getApp()->getRegister($name);
		return null;
	}
	
	public function __get($name)
	{
		return $this->getRegister($name);
	}
	
	/**
	 * Add layout to the page.
	 * The layouts you add will be render by the page.
	 * @see Ea_Page::add()
	 * 
	 * @param mixed $content
	 */
	public function add($content, $append=true)
	{
		$this->getPage()->add($content, $append);
	}

	/**
	 * Return the param from URL.
	 * Params are build to get the patern $_GET['module']['param'].
	 * @uses Ea_App::getParam()
	 * 
	 * @param string $name
	 * @param string $module
	 * @return string|numeric|null
	 */
	public function getParam($name, $default=null, $module=null)
	{
		$value=$this->getApp()->getParam($name, $module);
		if($value===null) return $default;
		return $value;
	}

	/**
	 * Return raw param from URL.
	 * 
	 * @param string $name
	 * @return string|numeric|null
	 */
	public function getRawParam($name, $default=null)
	{
		$value=$this->getApp()->getRawParam($name);
		if($value===null) return $default;
		return $value;
	}
	
	/**
	 * Get input id for GET form to feet with route params.
	 * 
	 * @param mixed $param
	 * @param string $module
	 * @return array
	 */
	public function getInputId($param, $module=null)
	{
		return $this->getApp()->getInputId($param, $module);
	}
}
?>