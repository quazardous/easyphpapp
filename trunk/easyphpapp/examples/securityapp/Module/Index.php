<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     examples
 * @subpackage  securityapp
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.3.3-20090122
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 * @filesource
 */

require_once 'Ea/Module/Abstract.php';
require_once 'Ea/Layout/Link.php';
require_once 'Ea/Layout/Text.php';
require_once 'Ea/Layout/Single.php';

/**
 * My basic module.
 *
 */
class Module_Index extends Ea_Module_Abstract
{

	public function actionIndex()
	{
		// set the page title
		$this->getPage()->setTitle('Public stuff');
		
		// add the text 'Hello <user>' to the page
		$this->getPage()->add('Hello'.($this->getRouter()->isConnectedUser()?' '.$this->getRouter()->getSecurity()->getConnectedUserLogin():'').', your are in public stuff.');

		// by the way, how to add non escaped code
		$this->getPage()->add(array('text'=>'<br/>', 'escape'=>false));

		// add a link to the 'user' action
		$this->getPage()->add(new Ea_Layout_Link($this->getRouter()->getRoute(null, 'user'), 'User stuff'));

		// classic <br/>
		$this->getPage()->add(new Ea_Layout_Single('br'));
		
		// add a link to the 'admin' action
		$this->getPage()->add(new Ea_Layout_Link($this->getRouter()->getRoute(null, 'admin'), 'Admin stuff'));

		// classic <br/>
		$this->getPage()->add(new Ea_Layout_Single('br'));
		
		// add a link to the 'logout' action
		if($this->getRouter()->isConnectedUser()) $this->getPage()->add(new Ea_Layout_Link($this->getRouter()->getRoute(null, 'logout'), 'Logout'));
		
		// router will call render
	}
	
	
	public function actionUser()
	{
		// set the page title
		$this->getPage()->setTitle('User stuff');
		
		// add the text 'Hello <user>' to the page
		$this->getPage()->add('Hello '.$this->getRouter()->getSecurity()->getConnectedUserLogin().', your are in user stuff.');

		// by the way, how to add non escaped code
		$this->getPage()->add(array('text'=>'<br/>', 'escape'=>false));
		
		// add a link to the 'admin' action
		$this->getPage()->add(new Ea_Layout_Link($this->getRouter()->getRoute(null, 'index'), 'Public stuff'));

		// classic <br/>
		$this->getPage()->add(new Ea_Layout_Single('br'));
		
		// add a link to the 'admin' action
		$this->getPage()->add(new Ea_Layout_Link($this->getRouter()->getRoute(null, 'admin'), 'Admin stuff'));

		// classic <br/>
		$this->getPage()->add(new Ea_Layout_Single('br'));
		
		// add a link to the 'logout' action
		$this->getPage()->add(new Ea_Layout_Link($this->getRouter()->getRoute(null, 'logout'), 'Logout'));
		
		// router will call render
	}
	
	public function actionAdmin()
	{
		// set the page title
		$this->getPage()->setTitle('Admin stuff');
		
		// add the text 'Hello <user>' to the page
		$this->getPage()->add('Hello '.$this->getRouter()->getSecurity()->getConnectedUserLogin().', your are in admin stuff.');

		// by the way, how to add non escaped code
		$this->getPage()->add(new Ea_Layout_Text('<br/>', false));

		// add a link to the 'admin' action
		$this->getPage()->add(new Ea_Layout_Link($this->getRouter()->getRoute(null, 'index'), 'Public stuff'));

		// classic <br/>
		$this->getPage()->add(new Ea_Layout_Single('br'));
		
		// add a link to the 'user' action
		$this->getPage()->add(new Ea_Layout_Link($this->getRouter()->getRoute(null, 'user'), 'User stuff'));

		// classic <br/>
		$this->getPage()->add(new Ea_Layout_Single('br'));
		
		// add a link to the 'logout' action
		$this->getPage()->add(new Ea_Layout_Link($this->getRouter()->getRoute(null, 'logout'), 'Logout'));
		
		// router will call render
	}
	
	public function actionLogout()
	{
		// logout action
		
		// disconnect registered user
		$this->getRouter()->getSecurity()->disconnect();
		
		// redirect to action 'index'
		$this->getRouter()->requestRedirect($this->getRouter()->getRoute(null, 'index'));
	}
}
