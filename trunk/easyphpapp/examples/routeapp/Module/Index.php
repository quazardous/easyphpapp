<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     examples
 * @subpackage  routeapp
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.1
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 * @filesource
 */

require_once 'Ea/Module/Abstract.php';
require_once 'Ea/Layout/Link.php';
require_once 'Ea/Layout/Single.php';

/**
 * My basic module.
 *
 */
class Module_Index extends Ea_Module_Abstract
{
	public function init()
	{
		// set the page title
		$this->getPage()->setTitle($this->getRouter()->getTargetModule().'::'.$this->getRouter()->getTargetAction());
	}
	
	public function actionIndex()
	{
		// add the text 'Hello World' to the page
		$this->getPage()->add('Current route (index) : '.$this->getRouter()->getTargetModule().'::'.$this->getRouter()->getTargetAction());
		$this->getPage()->add(new Ea_Layout_Single('br'));
		
		// add a link to the module 'index'
		$this->getPage()->add(new Ea_Layout_Link($this->getRouter()->getRoute(null, null, 'index'), 'index::<default action>'));
		$this->getPage()->add(new Ea_Layout_Single('br'));
		
		// add a link to the action 'index'
		$this->getPage()->add(new Ea_Layout_Link($this->getRouter()->getRoute(null, 'index'), '<current module>::index'));
		$this->getPage()->add(new Ea_Layout_Single('br'));

		// add a link to the action 'other'
		$this->getPage()->add(new Ea_Layout_Link($this->getRouter()->getRoute(null, 'other'), '<current module>::other'));
		$this->getPage()->add(new Ea_Layout_Single('br'));
		
		// add a link to the module 'other'
		$this->getPage()->add('default link : ');
		$route=$this->getRouter()->getRoute(null, null, 'other');
		$this->getPage()->add(new Ea_Layout_Link($route, 'other::<default action>'));
		$this->getPage()->add(new Ea_Layout_Single('br'));

		// add a link to the module 'other'
		$this->getPage()->add('link that don\'t propagate other modules params : ');
		$route=$this->getRouter()->getRoute(null, null, 'other');
		$route->propagate(false);
		$this->getPage()->add(new Ea_Layout_Link($route, 'other::<default action>'));
		$this->getPage()->add(new Ea_Layout_Single('br'));

		// add a link to the module 'other'
		$this->getPage()->add('same link with other module params (forced) : ');
		$route=$this->getRouter()->getRoute(null, null, 'other');
		$route->propagate(false);
		$route->setParam('foo', 'bar', 'index');
		$this->getPage()->add(new Ea_Layout_Link($route, 'other::<default action>'));
		$this->getPage()->add(new Ea_Layout_Single('br'));
		
		// add a link to the current module with lots of params
		$this->getPage()->add(new Ea_Layout_Link($this->getRouter()->getRoute(array('one'=>1, 'two'=>2)), 'params !'));
		$this->getPage()->add(new Ea_Layout_Single('br'));
		
		// router will call render
	}
	
	public function actionOther()
	{
		// add the text 'Hello World' to the page
		$this->getPage()->add('Current route : '.$this->getRouter()->getTargetModule().'::'.$this->getRouter()->getTargetAction());
		$this->getPage()->add(new Ea_Layout_Single('br'));

		// add a link to the module 'index'
		$this->getPage()->add(new Ea_Layout_Link($this->getRouter()->getRoute(null, null, 'index'), 'index::<default action>'));
		$this->getPage()->add(new Ea_Layout_Single('br'));
		
		// add a link to the action 'index'
		$this->getPage()->add(new Ea_Layout_Link($this->getRouter()->getRoute(null, 'index'), '<current module>::index'));
		$this->getPage()->add(new Ea_Layout_Single('br'));

		// add a link to the module 'other'
		$this->getPage()->add(new Ea_Layout_Link($this->getRouter()->getRoute(null, null, 'other'), 'other::<default action>'));
		$this->getPage()->add(new Ea_Layout_Single('br'));

		// add a link to the current module with lots of params
		$this->getPage()->add(new Ea_Layout_Link($this->getRouter()->getRoute(array('one'=>1, 'two'=>2)), 'params !'));
		$this->getPage()->add(new Ea_Layout_Single('br'));
		
		// router will call render
	}
	
}
?>