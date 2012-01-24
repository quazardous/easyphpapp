<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     examples
 * @subpackage  securityapp
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 * @filesource
 */

require_once 'Ea/Security/User/Abstract.php';

/**
 * Simple security user
 * 
 */
class Security_User extends Ea_Security_User_Abstract
{
	/**
	 * Given login.
	 * 
	 * @var string
	 */
	protected $_login=null;

	/**
	 * Given password.
	 * 
	 * @var string
	 */
	protected $_password=null;
	
	/**
	 * Users database.
	 * 
	 * @var array
	 */
	protected $_users=array(
		'john'=>array('password' => 'doe',   'roles' => array('user')),
		'aero'=>array('password' => 'smith', 'roles' => array('admin', 'user')),
	);
	
	/**
	 * User corresponding to user.
	 * 
	 * @var array
	 */
	protected $_user=null;
	
	/**
	 * Constructor.
	 * @see authenticate()
	 * 
	 * @param string $login
	 * @param string $password
	 * 
	 */
	public function __construct($login, $password)
	{
		$this->_login=$login;
		$this->_password=$password;
	}
	
	/**
	 * Authenticate method.
	 * @return boolean : true if valid user
	 */
	public function authenticate()
	{
		// doeas this login exists ?
		if(!array_key_exists($this->_login, $this->_users)) return false;
		
		// yes !
		$this->_user=$this->_users[$this->_login];
		
		// the test :)
		return $this->_users[$this->_login]['password']==$this->_password;
	}

	/**
	 * Return the unique login identifier of the user.
	 *
	 * @return string user login
 	 */
	public function getLogin()
	{
		return $this->_login;
	}
	
	/**
	 * Return array of string with user's roles.
	 *
	 * @return array of roles
	 */
	public function getRoles()
	{
		return (array)$this->_user['roles'];
	}
}