<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     examples
 * @subpackage  formapp
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.1
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Module/Abstract.php';
require_once 'Ea/Layout/Form.php';
require_once 'Ea/Layout/Input/Text.php';
require_once 'Ea/Layout/Input/Submit.php';
/**
 * My basic module.
 *
 */
class Module_Index extends Ea_Module_Abstract
{
	public function init()
	{
		// we will use session to display our inputs
		session_start();		
		
		// set the page title
		$this->getPage()->setTitle('Simple form');
	}
	
	public function actionIndex()
	{
		// declare new form
		$form=new Ea_Layout_Form('form1');
		
		// catch if some datas were send
		if($form->catchInput())
		{
			// do you form stuff
			$_SESSION['text1']=$form['text1'];
			
			// nothing else to do, router will redirect to current URL
			return;
		}
		
		// draw the form
		$form->add(new Ea_Layout_Input_Text('text1'));
		$form->add(new Ea_Layout_Input_Submit('send', 'Send'));
		
		// add the form to the page
		$this->getPage()->add($form);
		
		// if session, it means we hit send last time...
		if(isset($_SESSION['text1']))
		{
			$this->getPage()->add($_SESSION['text1']);
			// clean up
			unset($_SESSION['text1']);
		}
		
		// router will call render
	}
}
?>