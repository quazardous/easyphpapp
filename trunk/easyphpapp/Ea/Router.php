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

require_once 'Ea/Router/Exception.php';
require_once 'Ea/Route.php';
require_once 'Ea/Page.php';
require_once 'Ea/Module/Abstract.php';
require_once 'Zend/Loader.php';

/**
 * Router class.
 * The router is responsible to call the right module an action depending on the URL.
 * It manage a simple security layer.
 * @see Ea_Security
 * @uses Ea_Router_Exception
 * 
 */
class Ea_Router
{
	/**
	 * Param used to specify the module in URLs.
	 * 
	 * @var string
	 */
	protected $_moduleParamName='eam';

	/**
	 * Param used to specify the action in URLs.
	 * 
	 * @var string
	 */
	protected $_actionParamName='eaa';
	
	/**
	 * Default module.
	 * 
	 * @var string
	 */
	protected $_defaultModule='index';

	/**
	 * Default action.
	 * 
	 * @var string
	 */
	protected $_defaultAction='index';

	/**
	 * Default script.
	 * 
	 * @var string
	 */
	protected $_defaultScript='index.php?';

	/**
	 * If true, the router is responsible to render the targeted module.
	 * 
	 * @var boolean
	 */
	protected $_renderModule=true;
	
	/**
	 * Set if router must render the targeted module.
	 * 
	 * @param boolean $render
	 */
	public function setRenderModule($render)
	{
		$this->_renderModule=$render;
	}
	
	/**
	 * Targeted module.
	 * @see _analyze()
	 * 
	 * @var string
	 */
	protected $_targetModule=null;

	/**
	 * Targeted action.
	 * @see _analyze()
	 * 
	 * @var string
	 */
	protected $_targetAction=null;

	/**
	 * Targeted script.
	 * @see _analyze()
	 * 
	 * @var string
	 */
	protected $_targetScript=null;

	/**
	 * Running module.
	 * @see _execute()
	 * 
	 * @var string
	 */
	protected $_runningModule=null;

	/**
	 * Running action.
	 * @see _execute()
	 * 
	 * @var string
	 */
	protected $_runningAction=null;

	/**
	 * Running script.
	 * @see _execute()
	 * 
	 * @var string
	 * FIXME : useless
	 */
	protected $_runningScript=null;
	
	/**
	 * Singleton instance of router.
	 * 
	 * @var Ea_Router
	 */
	protected static $_singleton=null;

	/**
	 * Return singleton instance of router.
	 *
	 * @return Ea_Router
	 */
	public static function singleton()
	{
		if(!self::$_singleton)
		{
			self::$_singleton=new self();
		}
		return self::$_singleton;
	}
	
	/**
	 * Router constructor.
	 * Does nothing but is protected.
	 * 
	 */
	protected function __construct()
	{

	}
	
	/**
	 * Parse the current URL to determine targeted module and action.
	 * @uses $_defaultModule
	 * @uses $_defaultAction
	 */
	protected function _analyze()
	{
		$infos=parse_url($_SERVER['REQUEST_URI']);
		$this->_targetScript=$infos['path'].'?';
		if(isset($_GET[$this->_moduleParamName])) $this->_targetModule=$_GET[$this->_moduleParamName];
		else $this->_targetModule=$this->_defaultModule;
		if(isset($_GET[$this->_actionParamName])) $this->_targetAction=$_GET[$this->_actionParamName];
		else $this->_targetAction=$this->_defaultAction;
	}

	/**
	 * Return the URL string of the given route.
	 * 
	 * @param Ea_Route $route
	 * @return string
	 */
	public function url(Ea_Route $route)
	{
		$script=$route->getScript();
		//if($script===null) $script=$this->_targetScript;
		$get=$_GET;
		if($route->getModule())
		{
			$get[$this->_moduleParamName]=$route->getModule();
		}
		if($route->getAction())
		{
			$get[$this->_actionParamName]=$route->getAction();
		}
		if($route->getModule())
		{
			foreach($route->getParams() as $param => $value)
			{
				if($value!==null)$get[$get[$this->_moduleParamName]][$param]=urlencode($value);
			}
		}
		return $script.http_build_query($get);
	}

	/**
	 * Set the default script.
	 * @see $_defaultScript
	 * 
	 * @param string $script
	 */
	public function setDefaultScript($script)
	{
		$this->_defaultScript=$script;
	}
	
	/**
	 * Set the default module.
	 * @see $_defaultModule
	 * 
	 * @param string $script
	 */
	public function setDefaultModule($module)
	{
		$this->_defaultModule=$module;
	}

	/**
	 * Set the default action.
	 * @see $_defaultAction
	 * 
	 * @param string $action
	 */
	public function setDefaultAction($action)
	{
		$this->_defaultAction=$action;
	}
	
	/**
	 * Module class prefix.
	 * The router uses this prefix to figure the class name of a module.
	 * @see getModuleClassName()
	 * 
	 * @var string
	 */
	protected $_moduleClassPrefix='Ea_Module_';

	/**
	 * Set the module class prefix.
	 * @see $_moduleClassPrefix
	 * 
	 * @param string $prefix
	 */
	public function setModuleClassPrefix($prefix)
	{
		$this->_moduleClassPrefix=$prefix;
	}

	/**
	 * Get the module class prefix.
	 * @see $_moduleClassPrefix
	 * 
	 * @return string
	 */
	protected function getModuleClassPrefix()
	{
		return $this->_moduleClassPrefix;
	}
	
	/**
	 * Get the class name of a module.
	 * If there are '-' or '_', they are considered as word separator. Word will be separated with '_'. The first letter of each word is turned to uppercase.
	 * @see $_moduleClassPrefix
	 * @see Ea_Router
	 * 
	 * ie. foo => Ea_Module_Foo, foo-bar => Ea_Module_Foo_Bar
	 * 
	 * @param string|null $module : name of the module, if null uses current module
	 * @return string
	 */
	protected function getModuleClassName($module=null)
	{
		if(!$module)$module=$this->_targetModule;
		$class='';
		foreach(preg_split('/[-_]/',$module) as $word)
		{
			if($word)
			{
				$class.='_'.ucfirst($word);
			}
		}
		$class=$this->getModuleClassPrefix().'_'.$class;
		return preg_replace('/_+/','_',$class);
	}
	
	/**
	 * Get the action method name.
	 * If there are '-' or '_', they are considered as word separator. The first letter of each word is turned to uppercase.
	 * ie. action 'foo' will return 'actionFoo' and router will call $module->actionFoo().
	 * 
	 * @param string $action
	 * @param string $prefix
	 * @return string
	 */
	protected function getActionMethodName($action=null, $prefix='action')
	{
		if(!$action) $action=$this->_targetAction;
		$name='';
		foreach(preg_split('/[-_]/',$action) as $word)
		{
			if($word)
			{
				$name.=ucfirst($word);
			}
		}
		return $prefix.ucfirst($name);
	}
	
	/**
	 * Execute a given action.
	 * Call $module->init(), call the targeted action, call $module->complete(), call all pending callbacks and may call $module->render().
	 * 
	 * @uses getModuleClassName()
	 * @uses getActionMethodName()
	 * @uses Ea_Module_Abstract
	 * @uses Ea_Router_Exception
	 * @see addCallback()
	 * 
	 * @param string $module the module to trigger
	 * @param string $action the action of the given module to trigger
	 * @param string $prefix action method prefix
	 * @param $render render the module
	 */
	protected function _execute($module=null, $action=null, $prefix='action', $render=true)
	{
		if(!$module)$module=$this->_targetModule;
		if(!$action)$action=$this->_targetAction;
		if($module instanceof Ea_Module_Abstract)
		{
			$obj=$module;
			$this->_runningModule=$module->getName();
		}
		else
		{
			$this->_runningModule=$module;
			$moduleClasse=$this->getModuleClassName($module);
			$actionMethod=$this->getActionMethodName($action, $prefix);
			Zend_Loader::loadClass($moduleClasse);
			$obj=new $moduleClasse(array('router'=>$this, 'name'=>$module));
			if(!($obj instanceof Ea_Module_Abstract))
			{
				throw new Ea_Router_Exception("{$moduleClasse} does not extend Ea_Module_Abstract");
			}
		}
		$this->_runningAction=$action;
		$obj->_init();
		$obj->$actionMethod();
		$obj->_complete();
		$this->executeCallbacks();
		if($render&&$this->_renderModule) $obj->render();
		$this->_runningModule=null;
		$this->_runningAction=null;
	}
	
	/**
	 * Main router method.
	 * Parse the URL, call the security layer and trigger the targeted action in the targeted module.
	 * 
	 * @uses _analyze()
	 * @uses _security()
	 * @uses _execute()
	 * 
	 */
	public function dispatch()
	{
		$this->_analyze();
		$this->_security();
		$this->_execute();
	}
	
	/**
	 * Return the param from URL.
	 * Params are build to get the patern $_GET['module']['param'].
	 * @see Ea_Route
	 * 
	 * @param string $name
	 * @return string|numeric|null
	 */
	public function getParam($name)
	{
		if(!$this->_runningModule)
		{
			throw new Ea_Router_Exception("no running module");
		}
		if(isset($_GET[$this->_runningModule][$name]))
		{
			return $_GET[$this->_runningModule][$name];
		}
		return null;
	}
	
	/**
	 * Test if request is POST.
	 * 
	 * @return boolean
	 */
	public function isPost()
	{
		return $_SERVER['REQUEST_METHOD']=='POST';
	}
	
	/**
	 * Return a default route.
	 * Null elements are filled with current URL elements.     
	 * @see Ea_Route
	 *
	 * @param array $params
	 * @param string $action
	 * @param string $module
	 * @param string $script
	 * @param string $fragment
	 * 
	 * @return Ea_Route
	 */
	public function getRoute($params=null, $action=null, $module=null, $script=null, $fragment=null)
	{
		if(!$script)
		{
			$script=$this->_targetScript;
			//FIXME : zarb, pourquoi y a un if ici
		}
		else
		{
			if(!$module)
			{
				$module=$this->_targetModule;
			}
			else
			{
				if(!$action)$action=$this->_targetAction;
			}
		}
		//TODO : et les params ?
		return new Ea_Route($this, $script, $module, $action, $params, $fragment);
	}
	
	/**
	 * Redirect to given route.
	 * Send HTTP location header and may stop the script.
	 * 
	 * @param Ea_Route $route
	 * @param boolean $exit
	 */
	public function redirect(Ea_Route $route, $exit=true)
	{
		$location=$this->url($route);
		header("Location: {$location}");
		if($exit)exit;
	}
	
	/**
	 * The module to use for security challenge.
	 * @see Ea_Module_Security_Interface
	 * @see initSecurity()
	 * @see _security()
	 * 
	 * @var unknown_type
	 */
	protected $_securityModule=null;
	
	/**
	 * Security layer : if set security page enable.
	 * @see initSecurity()
	 * @see _security()
	 *
	 * @var Ea_Security
	 */
	protected $_security=null;

	public function initSecurity(Ea_Security $security, $module)
	{
		$this->_securityModule=$module;
		$this->_security=$security;
	}
	
	/**
	 * Try to get security layer.
	 * Throw an exception if no security.
	 * @see $_security
	 *
	 * @return Ea_Security
	 */
	public function getSecurity()
	{
		if(!$this->_security)
		{
			throw new Ea_Router_Exception("security is not initialized");
		}
		return $this->_security;
	}
	
	/**
	 * Handle security stuff.
	 * 
	 * If security is not initialized do nothing.
	 * 
	 * Test if a valid user is authenticated in the security layer.
	 * 
	 * If user is valid, test him against ACL.
	 * If user is allowed to reach the targeted module and action (or no ACL are defined), just let him pass.
	 * If user is not allowed, trigger the security module calling securityDeny() method.
	 * ie. securityDeny() can display an error message.
	 *  
	 * If user is not authenticated, trigger the security module calling getSecurityUser().
	 * If getSecurityUser() return null, trigger the security module calling securityChallenge() method.
	 * ie. securityChallenge() can display a login form.
	 * 
	 * @see Ea_Module_Security_Interface
	 * 
	 * @param boolean $render
	 */
	protected function _security($render=true)
	{
		if(!$this->_security) return;
		if($this->isConnectedUser())
		{
			if(!$this->isAllowed())
			{
				$this->_execute($this->_securityModule, 'deny', 'security');
				die();
			}
		}
		else
		{
			$c=$this->getModuleClassName($this->_securityModule);
			Zend_Loader::loadClass($c);
			$module=new $c($this);
			if(!(($module instanceof Ea_Module_Abstract)&&($module instanceof Ea_Module_Security_Interface)))
			{
				throw new Ea_Router_Exception("{$c} does not extend Ea_Module_Abstract or implements Ea_Module_Security_Interface");
			}
			//FIXME : call init just once => _init().
			$module->_init();
			if($user=$module->getSecurityUser())
			{
				$this->getSecurity()->authenticate($user);
				$module->_complete();
				$this->executeCallbacks();
				if($render&&$this->_renderModule) $module->render();
				die();
			}
			else {
				//FIXME : call init just once.
				$this->_execute($this->_securityModule, 'challenge', 'security');
				die();
			}
		}
	}
	
	/**
	 * Create standard array of names.
	 * @see allow()
	 * @see deny()
	 * 
	 * @param array|string $array turned to array(string)
	 * @param $type
	 */
	protected function _prepareArrayOfId(&$array, $type)
	{
		if($array!==null)
		{
			if(!is_array($array)) $array=array($array);
			foreach($array as &$entry) $entry="{$type}::{$entry}";
		}
	}
	
	/**
	 * Wrapper for security layer allow() method.
	 * @see Ea_Security::allow()
	 * 
	 * @param string $roles
	 * @param string|array(string) $modules
	 * @param string|array(string) $actions
	 */
	public function allow($roles, $modules=null, $actions=null)
	{
		$this->_prepareArrayOfId($modules, 'module');
		$this->_prepareArrayOfId($actions, 'action');
		$this->getSecurity()->allow($roles, $modules, $actions);		
	}

	/**
	 * Wrapper for security layer deny() method.
	 * @see Ea_Security::deny()
	 * 
	 * @param string $roles
	 * @param string|array(string) $modules
	 * @param string|array(string) $actions
	 */
	public function deny($roles, $modules=null, $actions=null)
	{
		$this->_prepareArrayOfId($modules, 'module');
		$this->_prepareArrayOfId($actions, 'action');
		$this->getSecurity()->deny($roles, $modules, $actions);		
	}
	
	/**
	 * Wrapper for security layer isConnectedUser() method.
	 * @see Ea_Security::isConnectedUser()
	 * 
	 * @return boolean
	 */
	public function isConnectedUser()
	{
		return $this->getSecurity()->isConnectedUser();
	}
	
	/**
	 * Test if user is allowed to trigger targeted module and action.
	 * @see Ea_Security::isAllowed()
	 * 
	 * @return boolean
	 */
	public function isAllowed()
	{
		return $this->getSecurity()->isAllowed("module::{$this->_targetModule}", "module::{$this->_targetAction}");
	}
	
	/**
	 * Application scope variables.
	 * 
	 * @var array(string=>mixed)
	 */
	protected $_registers=array();

	/**
	 * Set a register.
	 * @see $_registers.
	 * 
	 * @param string $name
	 * @param mixed $value by ref
	 */
	public function setRegister($name, &$value)
	{
		if($value===null&&array_key_exists($name, $this->_registers)) unset($this->_registers[$name]);
		else $this->_registers[$name]=&$value;
	}

	/**
	 * Get a register.
	 * @see $_registers.
	 * 
	 * @param $name
	 * @return mixed by ref
	 */
	public function &getRegister($name)
	{
		if(array_key_exists($name, $this->_registers)) return $this->_registers[$name];
		return null;
	}
	
	/**
	 * Get targeted module.
	 * @see $_targetModule
	 * 
	 * @return string
	 */
	public function getTargetModule()
	{
		return $this->_targetModule;
	}
	
	/**
	 * Get targeted action.
	 * @see $_targetAction
	 * 
	 * @return string
	 */
	public function getTargetAction()
	{
		return $this->_targetAction;
	}
	
	/**
	 * Default callback priority.
	 * @see addCallback()
	 * 
	 * @const
	 */
	const callbackPriority_default = 10;
	
	/**
	 * Last/max callback priority.
	 * @see addCallback()
	 * 
	 * @const
	 */
	const callbackPriority_last = 1000;
	
	/**
	 * Stack of callbacks to trigger after module complete() method.
	 * @see addCallback()
	 * 
	 * @var array(callback)
	 */
	protected $_callbackQueues=array();

	/**
	 * Add a callback to a given queue.
	 * The callbacks will be trigger after module complete() method and before render() method().
	 * @see call_user_func()
	 * @uses $_callbackQueues
	 * 
	 * @param string $queue
	 * @param callback $callback as defined for call_user_func()
	 * @param numeric $priority
	 * @param string $mode 'append', 'replace' or 'exclusive'. 'exclusive' will throw an exception if 2 exclusive callbacks are put in the same queue.
	 * @param $exit the queue is a terminal queue, exit() will be called after. Callbacks of a terminal queue take a boolean parameter to tell if they can exit by them selve or not.
	 */
	public function addCallback($queue, $callback, $priority=null, $mode='append', $exit=false)
	{
		if($priority===null) $priority=self::callbackPriority_default;
		if(!array_key_exists($queue, $this->_callbackQueues))
		{
			$this->_callbackQueues[$queue]=array('priority'=>$priority, 'exit'=>$exit, 'callbacks'=>array());
		}
		$this->_callbackQueues[$queue]['priority'] = $priority;
		$this->_callbackQueues[$queue]['exit'] = $exit;
		switch($mode)
		{
			case 'replace':
				$this->_callbackQueues[$queue]['callbacks']=array($callback);
				break;
			case 'exlusive':
				if(count($this->_callbacksQueues[$queue]['callbacks'])>0)
				{
					throw new Ea_Router_Exception("Failed to 'exclusive'' add callback to '{$queue}'' queue");
				}
				array_push($this->_callbackQueues[$queue]['callbacks'], $callback);
				break;
			default:
				array_push($this->_callbackQueues[$queue]['callbacks'], $callback);
				break;
		}
	}
	
	/**
	 * Used to sort the callback queues.
	 * 
	 * @param $a
	 * @param $b
	 * @return numeric
	 */
	static public function cmp_callback_queues(&$a, &$b)
	{
		return $b['priority']-$a['priority'];
	}
	
	/**
	 * Execute pending callbacks.
	 * May exit() if terminal queue are defined.
	 * 
	 * @uses $_callbackQueues
	 * @see addCallback()
	 * 
	 */
	protected function executeCallbacks()
	{
		uasort($this->_callbackQueues, array(__CLASS__, 'cmp_callback_queues'));
		foreach($this->_callbackQueues as $queue => $qdata)
		{
			if($qdata['exit'])
			{
				$exit=false;
				foreach($qdata['callbacks'] as $callback)
				{
					$exit=$exit||call_user_func($callback, false);
				}
				if($exit) exit;
			}
			else
			{
				foreach($qdata['callbacks'] as $callback)
				{
					call_user_func($callback);
				}
			}
		}
		
		$this->_callbackQueues=array();
	}
}
?>