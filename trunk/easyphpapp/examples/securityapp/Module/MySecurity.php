<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     examples
 * @subpackage  securityapp
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.1
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
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
		// set the page title
		$this->getPage()->setTitle('Login form');
		
		// declare the form
		$form=new Ea_Layout_Form('login');
		
		// build the form
		
		// add a table layout
		$form->add($table=new Ea_Layout_Table);
		
		// add the login row
		$table->addRow();
		$h=$table->addHeader('Login', array('align'=>'left'));
		$table->addCell(new Ea_Layout_Input_Text('login'));

		// add the password row
		$table->addRow();
		$table->addHeader('Password', array('align'=>'left'));
		$table->addCell(new Ea_Layout_Input_Password('password'));

		$table->addRow();
		$table->addHeader();
		$table->addCell(new Ea_Layout_Input_Submit('send', 'Send'));
		
		// add the form to the page
		$this->add($form);
		
		$this->add("Valid users are john/doe (user) and aero/smith (admin).");
		
		// router will call render
	}

	public function securityDeny()
	{
		// set the page title
		$this->getPage()->setTitle('Security error');
		
		// add a cool deny message
		$this->add($this->getRouter()->getSecurity()->getConnectedUserLogin()." you are not allowed to access ".$this->getRouter()->getTargetModule()."::".$this->getRouter()->getTargetAction());
		
		// classic <br/>
		$this->getPage()->add(new Ea_Layout_Single('br'));

		// add a back link
		$this->add(new Ea_Layout_Link('javascript:history.back()', 'Back'));
	}
	
	/**
	 * Return Ea_Security_User_Abstract.
	 * This method is called by the security layer if no user is registered.
	 *
	 * @return Ea_Security_User_Abstract
	 */
	public function getSecurityUser()
	{
		// declare the form
		$form=new Ea_Layout_Form('login');
	
		if($form->catchInput())
		{
			// instanciate our user with form data and return it to security layer
			return new Security_User($form['login'], $form['password']);
			// if the user match, the user will be registered and the targeted module and action will be triggered.
		}
		
		// returning null means 'no informations'.
		// security layer will call securityChallenge()
		return null;
	}
	
}