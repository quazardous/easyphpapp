<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     examples
 * @subpackage  securityapp
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id:$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 * @filesource
 */

require_once 'Ea/Layout/Form.php';
require_once 'Ea/Layout/Table.php';
require_once 'Ea/Layout/Input/Text.php';
require_once 'Ea/Layout/Input/Password.php';
require_once 'Ea/Layout/Input/Submit.php';
require_once 'Security/User.php';
require_once 'Ea/Module/Security/Interface.php';
require_once 'Ea/Layout/Link.php';
require_once 'Ea/Layout/Single.php';
require_once 'Ea/Module/Abstract.php';

/**
 * Security module.
 * A security module is a module usable by the security layer.
 * It must implements the interface Ea_Module_Security_Interface.
 *
 */
class Module_MySecurity extends Ea_Module_Abstract implements Ea_Module_Security_Interface
{	
	/**
	 * This method is called when no registered user is detected.
	 * 
	 */
	public function securityChallenge()
	{
		// not used here
	}

	/**
	 * This method is called when registered user is not allowed to access a module/action.
	 * 
	 */
	public function securityDeny()
	{
		// set the page title
		$this->getPage()->setTitle('Security error');
		
		// add a cool deny message
		$this->add($this->getApp()->getSecurity()->getConnectedUserLogin()." you are not allowed to access ".$this->getApp()->getTargetModule()."::".$this->getApp()->getTargetAction());
	}
	
	/**
	 * Return Ea_Security_User_Abstract.
	 * This method is called by the security layer if no user is registered.
	 *
	 * @return Ea_Security_User_Abstract
	 */
	public function getSecurityUser()
	{
		// instanciate our user with (dirty) get data and return it to security layer
		return new Security_User(isset($_GET['login'])?$_GET['login']:'', isset($_GET['password'])?$_GET['password']:'');
		// maybe you want more sophisticated test/behavior see complete()...
	}
	
	public function complete()
	{
		// complete is called after user authentication but before redirects...
		// so here you can test $this->getApp()->isAllowed() or whatever,
		// and use $this->getApp()->requestRedirect() to redirect user after good/bad login etc
	}
	
}