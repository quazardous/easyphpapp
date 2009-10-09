<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Application
 * @subpackage  Security
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.2.6.20081022
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Security/User/Password/Table/Exception.php';
require_once 'Ea/Security/User/Abstract.php';
require_once 'Zend/Db/Table/Abstract.php';
require_once 'Zend/Db/Table/Row/Abstract.php';
require_once 'Zend/Loader.php';

/**
 * User authentication inteface against Zend_Db_Table.
 * Can be used to test user with a table throw Zend_Db_Table.
 * 
 * @see Zend_Db_Table
 * 
 */
class Ea_Security_User_Password_Table extends Ea_Security_User_Abstract
{
	/**
	 * Zend_Db_Table instance.
	 *
	 * @var Zend_Db_Table
	 */
	protected $_dbTable;
	
	/**
	 * User login.
	 * 
	 * @var string
	 */
	protected $_login=null;

	/**
	 * User password as in database.
	 * 
	 * @var string
	 */
	protected $_password=null;
	
	/**
	 * Name of the table column of the login.
	 * 
	 * @var string
	 */
	protected $_loginCol=null;

	/**
	 * Name of the table column of the password.
	 * 
	 * @var string
	 */
	protected $_passwordCol=null;

	/**
	 * If defned, name of the table column of the role.
	 * In this implementation, user can have only one role.
	 * 
	 * @var string
	 */
	protected $_roleCol=null;
	
	/**
	 * Class name of the Zend_Db_Table.
	 * 
	 * @var string
	 */
	protected $_dbTableClass=null;
	
	/**
	 * Zend_Db_Table_Row instance : the user row.
	 *
	 * @var Zend_Db_Table_Row
	 */
	protected $_row=null;
	
	/**
	 * Cols you want to keep track in session.
	 * 
	 * @var array(string)
	 */
	protected $_attributesCols=array();
	
	/**
	 * Return the user row.
	 * @see $_row
	 *
	 * @return Zend_Db_Table_Row
	 */
	public function getRow()
	{
		return $this->_row;
	}
	
	/**
	 * Constructor.
	 * Many combinations of parameter is possible.
	 * @see authenticate()
	 * 
	 * @param array(string=>mixed) $config associative array of parameters
	 * 
	 * - db_table : an instance of Zend_Db_Table_Abstract
	 * 
	 * - db_table_class : the name of the class
	 * 
	 * - password : the password to test
	 * 
	 * - login : the login to test
	 * 
	 * - password_col (mandatory) : the password column
	 * 
	 * - login_col : the login column
	 * 
	 * - role_col : the role column
	 * 
	 * - row : Zend_Db_Table_Row_Abstract instance
	 * 
	 * - attributes_cols : array of cols you want to keep in session => getAttributes()
	 * 
	 */
	public function __construct(array $config)
	{
		if(array_key_exists('db_table', $config))
		{
			$this->_dbTable=$config['db_table'];
			if(!($this->_dbTable instanceof Zend_Db_Table_Abstract))
			{
				throw new Ea_Security_User_Password_Table_Exception("db_table is not an instance of Zend_Db_Table_Abstract");
			}
		}
		if(array_key_exists('db_table_class', $config)) $this->_dbTableClass=$config['db_table_class'];
		if(array_key_exists('password', $config)) $this->_password=$config['password'];
		if(array_key_exists('login', $config)) $this->_login=$config['login'];
		if(array_key_exists('password_col', $config)) $this->_passwordCol=$config['password_col'];
		if(array_key_exists('attributes_cols', $config)) $this->_attributesCols=(array)$config['attributes_cols'];
		if(array_key_exists('login_col', $config)) $this->_loginCol=$config['login_col'];
		if(array_key_exists('role_col', $config)) $this->_roleCol=$config['role_col'];
		if(array_key_exists('row', $config))
		{
			$this->_row=$config['row'];
			if(!($this->_row instanceof Zend_Db_Table_Row_Abstract))
			{
				throw new Ea_Security_User_Password_Table_Exception("row is not an instance of Zend_Db_Table_Row_Abstract");
			}
		}
		if(!$this->_passwordCol)
		{
			// at least user must provide the password col
			throw new Ea_Security_User_Table_Exception("no password col");
		}
	}
	
	/**
	 * Authenticate method.
	 * Depending on the parameters authenticate() will try to find out how to test the user.
	 * 
	 * - If 'row' was given but no 'login', try to use 'login_col' on 'row' to set a 'login'. Throw an exception if no 'login_col' and no 'login'.
	 * 
	 * - If no 'row'. Throw an exception if no 'login'. Throw an exception if no 'login_col'.
	 * 
	 * - If no 'db_table', try to instanciate one with 'db_table_class', throw an exception if can not.
	 * 
	 * - Do a select on the table, if not exactly one row, throw an exception.
	 *
	 * @return boolean : true if valid user
	 */
	public function authenticate()
	{
		if($this->_row)
		{
			if(!$this->_login)
			{
				// if row given try to set the login with login_col
				if(!$this->_loginCol)
				{
					throw new Ea_Security_User_Password_Table_Exception("no login col");
				}
				$loginCol=$this->_loginCol;
				$this->_login=$this->_row->$loginCol;
			}
		}
		else
		{
			// if no row given try to retrieve it from table
			
			if(!$this->_loginCol)
			{
				throw new Ea_Security_User_Password_Table_Exception("no login col");
			}
			
			if(!$this->_login)
			{
				throw new Ea_Security_User_Password_Table_Exception("no login");
			}
			
			if(!$this->_dbTable)
			{
				if(!$this->_dbTableClass)
				{
					throw new Ea_Security_User_Password_Table_Exception("no db_table_class given");
				}
				$c=$this->_dbTableClass;
				Zend_Loader::loadClass($c);
				$this->_dbTable=new $c();
				if(!($this->_dbTable instanceof Zend_Db_Table_Abstract))
				{
					throw new Ea_Security_User_Password_Table_Exception("$c does not extend Zend_Db_Table_Abstract");
				}
			}	
				
			$select=$this->_dbTable->select();
			$select->where("{$this->_loginCol} = ?", $this->_login);
			$results=$this->_dbTable->fetchAll($select);
			if(count($results)==0)
			{
				$this->setAuthCode(self::authCode_IDENTITY_NOT_FOUND);
				return false;
			}
			if(count($results)>1)
			{
				$this->setAuthCode(self::authCode_IDENTITY_AMBIGUOUS);
			}
			$this->_row=$results->getRow(0);
		}
		$passwordCol=$this->_passwordCol;
		if($this->_row->$passwordCol==$this->_password)
		{
			$this->setAuthCode(self::authCode_SUCCES);
			return true;
		}
		$this->setAuthCode(self::authCode_FAILURE);
		return false;
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
		if(!($this->_roleCol&&$this->_row)) return null;
		$roleCol=$this->_roleCol;
		return array($this->_row->$roleCol);
	}
	
	/**
	 * Return array of string with user's attributes.
	 *
	 * @return array of attributess
	 */
	public function getAttributes()
	{
		if(!($this->_row)) return null;
		$ret=array();
		foreach($this->_attributesCols as $col)
		{
			$ret[$col]=$this->_row->$col;
		}
		return $ret;
	}
	
}
?>