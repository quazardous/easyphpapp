<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Router
 * @subpackage  Router
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.1
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

/**
 * Route class.
 * The route is a structured way to represent url.
 * @see Ea_Router
 * 
 */
class Ea_Route
{
	/**
	 * Script part of the url.
	 * 
	 * @var string
	 */
	protected $_script=null;

	/**
	 * Module to trigger.
	 * 
	 * @var string
	 */
	protected $_module=null;
	
	/**
	 * Action to trigger.
	 * 
	 * @var string
	 */
	protected $_action=null;

	/**
	 * Params to put in the query part.
	 * 
	 * @var array
	 */
	protected $_params=null;

	/**
	 * Fragement is what is behind th #.
	 * 
	 * @var string
	 */
	protected $_fragment=null;

	/**
	 * Ea_Router handler
	 *
	 * @var Ea_Router
	 */
	protected $_router=null;
	
	protected $_url=null;
	
	/**
	 * Route constructor.
	 * 
	 * @param Ea_Router $router
	 * @param string $script
	 * @param string $module
	 * @param string $action
	 * @param array $params
	 * @param string $fragment
	 */
	public function __construct(Ea_Router $router, $script=null, $module=null, $action=null, $params=array(), $fragment=null)
	{
		$this->_router=$router;
		$this->_script=$script;
		$this->_module=$module;
		$this->_action=$action;
		$this->_params=$params;
		$this->_fragment=$fragment;
	}

	/**
	 * Get url string.
	 * @uses Ea_Router:url()
	 * 
	 * @return string
	 */
	public function url()
	{
		if(!$this->_url) $this->_url=$this->_router->url($this);
		if($this->_fragment) return $this->_url.'#'.$this->_fragment;
		return $this->_url;
	}

	/**
	 * Return the associative array of params.
	 *
	 * @return array(string=>string)
	 */
	public function getParams()
	{
		if(is_array($this->_params)) return $this->_params;
		return array();
	}

	/**
	 * Set the script.
	 * @see $_script
	 *  
	 * @param string $script
	 */
	public function setScript($script)
	{
		$this->_script=$script;
		$this->_url=null;
	}

	/**
	 * Get the script.
	 * @see $_script
	 * 
	 * @return string
	 */
	public function getScript()
	{
		return $this->_script;
	}

	/**
	 * Set the fragment.
	 * @see $_fragment
	 * 
	 * @param string $fragment
	 */
	public function setFragment($fragment)
	{
		$this->_fragment=$fragment;
	}

	/**
	 * Get the fragment.
	 * @see $_fragment
	 * 
	 * @return string
	 */
	public function getFragment()
	{
		return $this->_fragment;
	}
	
	/**
	 * Set the module.
	 * @see $_module
	 * 
	 * @param string $module
	 */
	public function setModule($module)
	{
		$this->_module=$module;
		$this->_url=null;
	}

	/**
	 * Get the module.
	 * @see $_module
	 * 
	 * @return string
	 */
	public function getModule()
	{
		return $this->_module;
	}

	/**
	 * Set the action.
	 * @see $_action
	 * 
	 * @param string $action
	 */
	public function setAction($action)
	{
		$this->_action=$action;
		$this->_url=null;
	}
	
	/**
	 * Get the action.
	 * 
	 * @return string
	 */
	public function getAction()
	{
		return $this->_action;
	}
		
	/**
	 * Get a given param value.
	 * 
	 * @param string $name
	 * @return string|numeric
	 */
	public function getParam($name)
	{
		if(isset($this->_params[$name])) return $this->_params[$name];
		return null;		
	}

	/**
	 * Set a given param value.
	 * 
	 * @param string $name
	 * @param string|numeric $value
	 */
	public function setParam($name, $value)
	{
		if(isset($this->_params[$name])&&$value===null) unset($this->_params[$name]);
		else $this->_params[$name]=$value;
	}
	
}
?>