<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Router
 * @subpackage  Security
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.1
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Zend/Acl.php';
require_once 'Zend/Acl/Role.php';
require_once 'Zend/Acl/Resource.php';
require_once 'Zend/Auth.php';
require_once 'Zend/Auth/Result.php';
require_once 'Zend/Auth/Storage/Session.php';
require_once 'Ea/Security/Adapter.php';

/**
 * Security layer class.
 * The security layer is responsible to authenticate and allow users.
 * 
 * Ea_Security is design to facilitate the use of Zend_Auth and Zend_Acl in EasyPhpApp.
 * 
 * @see Zend_Auth
 * @see Zend_Acl
 * 
 */
class Ea_Security
{
	/**
	 * Security layer singleton.
	 * 
	 * @var Ea_Security
	 */
	protected static $_singleton=null;

	/**
	 * Security layer singleton factory
	 * @uses $_singleton
	 *
	 * @return Ea_Security
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
	 * Security layer constructor.
	 * 
	 */
	protected function __construct()
	{
		$this->_auth=Zend_Auth::getInstance();
	}
	
	/**
	 * Zend_Auth handler.
	 *
	 * @var Zend_Auth
	 */
	protected $_auth=null;
	
	/**
	 * Namespace used in session.
	 * 
	 * @var string
	 */
	protected $_sessionNamespace='Ea_Security';

	/**
	 * Set session namespace.
	 * @uses $_sessionNamespace
	 * 
	 * @param string $name
	 */
	public function setSessionNamespace($namespace)
	{
		$this->_sessionNamespace=$namespace;
	}

	/**
	 * Get session namespace.
	 * 
	 * @return string
	 */
	public function getSessionNamespace()
	{
		return $this->_sessionNamespace;
	}
	
	/**
	 * Zend_Auth_Result handler.
	 * Used to keep trace of code.
	 *
	 * @var Zend_Auth_Result
	 */
	protected $_result=null;
	
	/**
	 * Boolean used to know if init() was called.
	 * 
	 * @var boolean
	 */
	protected $_init=false;
	

	/**
	 * Init security layer.
	 * Called only once.
	 * 
	 * @uses $_init
	 */
	protected function init()
	{
		$this->_init=true;
		$this->_auth->setStorage(new Zend_Auth_Storage_Session($this->getSessionNamespace()));
	}
	
	/**
	 * Authenticate the user. Return true if user is authenticated.
	 * Call $user->authenticate(). You can use getCode() to get more detail.
	 * @see Ea_Security_User_Abstract
	 * @see getCode()
	 * 
	 * @param Ea_Security_User_Abstract $user
	 * @return boolean
	 */
	public function authenticate(Ea_Security_User_Abstract $user)
	{
		$this->init();
		$this->_result=$this->_auth->authenticate(new Ea_Security_Adapter($user));
		return $this->_result->isValid();
	}

    /**
     * General Failure
     */
    const authCode_FAILURE = 0;

    /**
     * Failure due to identity not being found.
     */
    const authCode_IDENTITY_NOT_FOUND     = -1;

    /**
     * Failure due to identity being ambiguous.
     */
    const authCode_IDENTITY_AMBIGUOUS     = -2;

    /**
     * Failure due to invalid credential being supplied.
     */
    const authCode_CREDENTIAL_INVALID     = -3;

    /**
     * Failure due to uncategorized reasons.
     */
    const authCode_UNCATEGORIZED          = -4;

    /**
     * Authentication success.
     */
    const authCode_SUCCES = 1;
	
	/**
	 * Return code for last authenticate().
	 * @see authenticate()
	 * 
	 * @return authCode
	 */
	public function getCode()
	{
		switch($this->_result->getCode())
		{
        	case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND: return self::authCode_IDENTITY_NOT_FOUND; break;
        	case Zend_Auth_Result::FAILURE_IDENTITY_AMBIGUOUS: return self::authCode_IDENTITY_AMBIGUOUS; break;
        	case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID: return self::authCode_CREDENTIAL_INVALID; break;
        	case Zend_Auth_Result::FAILURE_UNCATEGORIZED: return self::authCode_UNCATEGORIZED; break;
        	default: self::authCode_FAILURE;
		}
	}
	
	/**
	 * Test if an user is registered.
	 * 
	 * @return boolean
	 */
	public function isConnectedUser()
	{
		$this->init();
		return $this->_auth->hasIdentity();
	}
	
	/**
	 * Get the login of the registered user.
	 * 
	 * @return string
	 */
	public function getConnectedUserLogin()
	{
		$this->init();
		$identity=$this->_auth->getIdentity();
		return $identity['login'];
	}

	/**
	 * Get the roles of an user.
	 * 
	 * @return array(string)
	 */
	public function getConnectedUserRoles()
	{
		$this->init();
		$identity=$this->_auth->getIdentity();
		return $identity['roles'];
	}
	
	/**
	 * Test if user has the given role.
	 * 
	 * @param string $role
	 * @return boolean
	 */
	public function connectedUserHasRole($role)
	{
		$this->init();
		$identity=$this->_auth->getIdentity();
		return array_key_exists($role, $identity['roles']);
	}
	
	/**
	 * Disconnect user.
	 * Clear informations of registered user.
	 * 
	 */
	public function disconnect()
	{
		$this->init();
		$this->_auth->clearIdentity();
	}
	
	/**
	 * ACL handler.
	 * If null (default), no ACL are tested.
	 * @see allow()
	 * @see deny()
	 *
	 * @var Zend_Acl
	 */
	protected $_acl=null;
	
	/**
	 * Internal resource array.
	 * 
	 * @var array(string=>Zend_Acl_Resource)
	 */
	protected $_resources=array();

	/**
	 * Get/set resource handler.
	 * @uses $_resources
	 * 
	 * @param string $resourceId
	 * @return Zend_Acl_Resource
	 */
	protected function getResourceHandler($resourceId)
	{
		if(!array_key_exists($resourceId, $this->_resources))
		{
			$this->_resources[$resourceId]=new Zend_Acl_Resource($resourceId);
		}
		return $this->_resources[$resourceId];
	}
	
	/**
	 * Internal role array.
	 * 
	 * @var array(string=>Zend_Acl_Role)
	 */
	protected $_roles=array();

	/**
	 * Get/set role handler.
	 * @uses $_roles
	 * 
	 * @param string $roleId
	 * @return Zend_Acl_Role
	 */
	protected function getRoleHandler($roleId)
	{
		if(!array_key_exists($roleId, $this->_roles))
		{
			$this->_roles[$roleId]=new Zend_Acl_Role($roleId);
			
		}
		return $this->_roles[$roleId];
	}
	
	/**
	 * Initialize ACL layer.
	 * Methods using ACL always call initACL().
	 * 
	 */
	protected function initAcl()
	{
		if(!$this->_acl) $this->_acl=new Zend_Acl();
	}
	
	/**
	 * Declare new roles.
	 * addRole() is not mandatory for simple roles, but roles are hierachical, so addRole() is usefull to define parent role.
	 * 
	 * @param string|array(string) $roleIds
	 * @param string|array(string) $parents
	 * @return numeric
	 */
	public function addRole($roleIds, $parents=null)
	{
		$this->initAcl();
		if(!is_array($roleIds))
		{
			$roleIds=array($roleIds);
		}
		if($parents!==null)
		{
			if(!is_array($parents)) $parents=array($patents);
			foreach($parents as $parent)
			{
				$this->addRole($parent);
			}
		}
		$ret=0;
		foreach($roleIds as $roleId)
		{
			if(!array_key_exists($roleId, $this->_roles))
			{
				$this->_roles[$roleId]=new Zend_Acl_Role($roleId);
				$this->_acl->addRole($this->_roles[$roleId], $parents);
				$ret++;
			}
		}
		return $ret;
	}

	/**
	 * Declare new resources.
	 * addResource() is not mandatory for simple resources, but resources are hierachical, so addResource() is usefull to define parent resource.
	 * 
	 * @param string|array(string) $resourceIds
	 * @param string|array(string) $parents
	 * @return numeric
	 */
	public function addResource($resourceIds, $parents=null)
	{
		$this->initAcl();
		if(!is_array($resourceIds))
		{
			$resourceIds=array($resourceIds);
		}
		if($parents!==null)
		{
			if(!is_array($parents)) $parents=array($patents);
			foreach($parents as $parent)
			{
				$this->addResource($parent);
			}
		}
		$ret=0;
		foreach($resourceIds as $resourceId)
		{
			if(!array_key_exists($resourceId, $this->_resources))
			{
				$this->_resources[$resourceId]=new Zend_Acl_Resource($resourceId);
				$this->_acl->add($this->_resources[$resourceId], $parents);
				$ret++;
			}
		}
		return $ret;
	}
	
	/**
	 * Allow roles to resources with privileges.
	 * @see Zend_Acl::allow()
	 * 
	 * @param string|array(string) $roleIds
	 * @param string|array(string) $resourceIds
	 * @param string|array(string) $privilegeIds
	 */
	public function allow($roleIds, $resourceIds=null, $privilegeIds=null)
	{
		$this->initAcl();
		$this->addRole($roleIds);
		$this->addResource($resourceIds);
		$this->_acl->allow($roleIds, $resourceIds, $privilegeIds);
	}
	
	/**
	 * Deny roles to resources with privileges.
	 * @see Zend_Acl::deny()
	 * 
	 * @param string|array(string) $roleIds
	 * @param string|array(string) $resourceIds
	 * @param string|array(string) $privilegeIds
	 */
	public function deny($roleIds, $resourceIds=null, $privilegeIds=null)
	{
		$this->initAcl();
		$this->addRole($roleIds);
		$this->addResource($resourceIds);
		$this->_acl->deny($roleIds, $resourceIds, $privilegeIds);
	}
	
	/**
	 * Test if user is allowed to access resource with privilege.
	 * @see Zend_Acl::isAllowed()
	 * 
	 * @param string $resourceId
	 * @param string $privilegeId
	 * @return numeric
	 */
	public function isAllowed($resourceId = null, $privilegeId = null)
    {
    	// if no rules always allowed...
		if(!$this->_acl) return true;
		$roles=$this->getConnectedUserRoles();
		// if no role always deny...
		if($roles===null) return false;
		if(!is_array($roles)) $roles=array($roles);
		$allowed=false;
		//TODO : add user test
		// allowed if one of the user's role is allowed
		foreach($roles as $roleId) $allowed=$allowed||$this->_acl->isAllowed($roleId, $resourceId, $privilegeId);
		return $allowed;
	}
}
?>