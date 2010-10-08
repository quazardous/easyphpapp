<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Application
 * @subpackage  Security
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.3.6-20090210
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

/**
 * Security user abstract class.
 * This class is an interface with the security layer.
 * You have to define the way EasyPhpApp can authenticate user.
 * @see Ea_Security_User_Password_Table for an example of implementation.
 * 
 * @see Ea_Security
 * 
 */
abstract class Ea_Security_User_Abstract
{
	/**
	 * Authenticate method : must be defined by client.
	 * authenticate() is called once if no user is registered in the security layer.
	 * If authenticate() return true, the user is registered in the security layer.
	 *
	 * @return boolean : true if valid user
	 */
	abstract public function authenticate();

	/**
	 * Return the unique login identifier of the user
	 *
	 * @return string : user login
 	 */
	abstract public function getLogin();	
	
	/**
	 * Return array of string with user's roles
	 *
	 * @return array roles
	 */
	public function getRoles()
	{
		// default : role is user login.
		return $this->getLogin();
	}

	/**
	 * Return array of user attributes roles.
	 * It can be any attribute you want to keep track in the session.
	 *
	 * @return array attributes
	 */
	public function getAttributes()
	{
		// default is no attributes
		return array();
	}
	
	protected $_isAuth=null;
	public function isAuth()
	{
		if($this->_isAuth===null) $this->_isAuth=$this->authenticate();
		return $this->_isAuth;
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
	 * Keep track of specific authenticate() status.
	 * 
	 * @var authCode
	 */
	protected $_authCode=null;

	/**
	 * Set code.
	 * @see $_authCode
	 * 
	 * @param authCode $code
	 */
	public function setAuthCode($code)
	{
		$this->_authCode=$code;
	}
	
	/**
	 * Get code.
	 * @see $_authCode
	 * 
	 * @return authCode
	 */
	public function getAuthCode()
	{
		return $this->_authCode;
	}
	
	/**
	 * Keep track of specific authenticate() message.
	 * 
	 * @var string
	 */
	protected $_authMessage=null;

	/**
	 * Get message.
	 * 
	 * @return string
	 */
	public function getAuthMessage()
	{
		return $this->_authMessage;
	}

	/**
	 * Enter description here...
	 * 
	 * @param string $message
	 * @return unknown_type
	 */
	public function setAuthMessage($message)
	{
		$this->_authMessage=$message;
	}
	
}
