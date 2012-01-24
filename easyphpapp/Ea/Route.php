<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Application
 * @subpackage  Application
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/App.php';
require_once 'Ea/Route/Exception.php';

/**
 * Route class.
 * The route is a structured way to represent internal url.
 * @see Ea_App
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
	protected $_params=array();

	/**
	 * Params for other module to put in the query part.
	 * 
	 * @var array(string=>array))
	 */
	protected $_modulesParams=array();

	/**
	 * Raw params to put in the query part.
	 * 
	 * @var array(string=>array))
	 */
	protected $_rawParams=array();
	
	/**
	 * Fragement is what is behind th #.
	 * 
	 * @var string
	 */
	protected $_fragment=null;

	/**
	 * Ea_App handler
	 *
	 * @var Ea_App
	 */
	protected $_app=null;
	
	/**
	 * Url string. Used for optimization.
	 * 
	 * @var string
	 */
	protected $_url=null;
	
	/**
	 * Define if route must propagate existing params.
	 * 
	 * @var boolean|array
	 * - false : don't propagate other modules params
	 * - true : propagate other modules params
	 * - array(string) : propagate given modules params or raw params
	 */
	protected $_propagate=true;
	
	/**
	 * Set propagate.
	 * @see $_propagate
	 * 
	 * @param boolean|string|array(string) $propagate
	 */
	public function propagate($propagate)
	{
		$this->_url=null;
		if(is_array($propagate))
		{
			$this->_propagate=array();
			foreach($propagate as $module)
			{
				array_push($this->_propagate, Ea_App::standardize($module));
			}
		}
		else if(is_string($propagate))
		{
			$this->_propagate=array(Ea_App::standardize($propagate));
		}
		else if($propagate)
		{
			$this->_propagate=true;
		}
		else
		{
			$this->_propagate=false;
		}
	}
	
	/**
	 * Get propagate.
	 * @see $_propagate
	 * 
	 * @return boolean|array
	 */
	public function getPropagate()
	{
		return $this->_propagate;
	}
	
	/**
	 * Route constructor.
	 * 
	 * @param Ea_App $app
	 * @param string $script
	 * @param string $module
	 * @param string $action
	 * @param array $params for the given module
	 * @param string $fragment
	 */
	public function __construct(Ea_App $app, $script=null, $module=null, $action=null, $params=null, $fragment=null)
	{
		$this->_app=$app;
		$this->_script=$script;
		if($module)$this->setModule($module);
		if($action)$this->setAction($action);
		$this->_params=is_array($params)?$params:array();
		$this->_fragment=$fragment;
	}

	/**
	 * Get url string.
	 * @uses Ea_App:url()
	 * 
	 * @return string
	 */
	public function url()
	{
		if(!$this->_url) $this->_url=$this->_app->url($this);
		if($this->_fragment) return $this->_url.'#'.$this->_fragment;
		return $this->_url;
	}

	/**
	 * Return an array of associative arrays of params for each module.
	 *
	 * @param string $module target module
	 * 
	 * @return array(string=>array(string=>string))
	 */
	public function getModuleParams()
	{
		return $this->_modulesParams;
	}
	
	/**
	 * Return an associative arrays of params.
	 *
	 * @param string $module target module
	 * 
	 * @return array(string=>string)
	 */
	public function getParams()
	{
		return $this->_params;
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
		$this->_module=Ea_App::standardize($module);
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
		$this->_action=Ea_App::standardize($action);
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
	 * @param string $module if null will impact route module
	 * 
	 * @return string|numeric
	 */
	public function getParam($name, $module=null)
	{
		if($module)
		{
			$module=Ea_App::standardize($module);
			if(!array_key_exists($module, $this->_modulesParams)) return null;
			if(!array_key_exists($name, $this->_modulesParams[$module])) return null;
			return $this->_modulesParams[$module][$name];
		}
		if(isset($this->_params[$name])) return $this->_params[$name];
		return null;		
	}

	/**
	 * Set a given param value.
	 * 
	 * @param string $name
	 * @param string|numeric $value
	 * @param string $module if null will impact route module
	 */
	public function setParam($name, $value, $module=null)
	{
		$this->_url=null;
		if($module)
		{
			$module=Ea_App::standardize($module);
			if(!array_key_exists($module, $this->_modulesParams)) $this->_modulesParams[$module]=array();
			$this->_modulesParams[$module][$name]=$value;
		}
		else
		{
			$this->_params[$name]=$value;
		}
	}
	
	/**
	 * Add a raw param (no module).
	 * 
	 * @param string $name
	 * @param string $value
	 */
	public function setRawParam($name, $value)
	{
		$this->_url=null;
		$this->_rawParams[$name]=$value;
	}
	
	/**
	 * Get a raw param.
	 * 
	 * @param string $name
	 * @return string
	 */
	public function getRawParam($name)
	{
		if(array_key_exists($name, $this->_rawParams)) return $this->_rawParams[$name];
		return null;
	}
	
	/**
	 * Get all raw params.
	 * 
	 * @return array
	 */
	public function getRawParams()
	{
		return $this->_rawParams;
	}
	
}
