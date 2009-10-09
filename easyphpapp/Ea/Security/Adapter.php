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

require_once 'Zend/Auth/Adapter/Interface.php';
require_once 'Ea/Security/User/Abstract.php';
require_once 'Zend/Auth/Result.php';

/**
 * Adapter between Ea_Security_User_Abstract and Zend_Auth.
 * 
 * @see Zend_Auth
 * @see Ea_Security_User_Abstract
 * 
 */
class Ea_Security_Adapter implements Zend_Auth_Adapter_Interface
{
	/**
	 * Ea_Security_User_Abstract instance.
	 *
	 * @var Ea_Security_User_Abstract
	 */
	protected $_user=null;
	
	/**
	 * Return security user.
	 *
	 * @return Ea_Security_User_Abstract
	 */
	public function getUser()
	{
		return $this->_user;
	}
	
    /**
     * Constructor.
     *
     * @param Ea_Security_User_Abstract $user
     */
    public function __construct(Ea_Security_User_Abstract $user)
    {
       $this->_user=$user;
    }

    /**
     * Try to authenticate user.
     *
     * @throws Zend_Auth_Adapter_Exception if errors occure
     * @return Zend_Auth_Result
     */
    public function authenticate()
    {
        if($this->_user->authenticate())
        {
        	$code=Zend_Auth_Result::SUCCESS;
        }
        else
        {
        	switch($this->_user->getAuthCode())
        	{
        		case Ea_Security_User_Abstract::authCode_IDENTITY_NOT_FOUND: $code=Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND; break;
        		case Ea_Security_User_Abstract::authCode_IDENTITY_AMBIGUOUS: $code=Zend_Auth_Result::FAILURE_IDENTITY_AMBIGUOUS; break;
        		case Ea_Security_User_Abstract::authCode_CREDENTIAL_INVALID: $code=Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID; break;
        		case Ea_Security_User_Abstract::authCode_UNCATEGORIZED: $code=Zend_Auth_Result::FAILURE_UNCATEGORIZED; break;
        		default: $code=Zend_Auth_Result::FAILURE;
        	}
        }
        return new Zend_Auth_Result($code, array('login'=>$this->_user->getLogin(), 'roles'=>$this->_user->getRoles(), 'attributes'=>$this->_user->getAttributes()), (array)$this->_user->getAuthMessage());
    }	
}
?>