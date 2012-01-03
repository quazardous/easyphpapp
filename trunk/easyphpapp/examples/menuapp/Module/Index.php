<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     examples
 * @subpackage  basicapp
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 * @filesource
 */

require_once 'Ea/Module/Abstract.php';

/**
 * My basic module.
 *
 */
class Module_Index extends Ea_Module_Abstract
{
	public function init()
	{
	  parent::init();
		$this->getPage()->setTitle('Menu integration');
		
		require_once 'Ea/Layout/Container.php';
		$this->add($div = new Ea_Layout_Container);
		$div->addAttribute('class', 'menu-container');
		
		$this->getPage()->addStyle('css/memu-0.1.css');
		$this->getPage()->addStyle('famfamfam/css-sprite.css');
		require_once 'Ea/Layout/Menu.php';
		$div->add($menu = new Ea_Layout_Menu);
		
		$item = $menu->addItem('Test 1');
		$item->setIcon('cd');
		$menu->addItem('Test 2');
		$menu->addItem('Test 3');
		
		$item->add($sub1 = new Ea_Layout_Menu);
		
		$item = $sub1->additem('Test 1.1');
		
		$sub1->additem('Test 1.2');
		
		$item->add($sub2 = new Ea_Layout_Menu);
		
		require_once 'Ea/Layout/Link.php';
		$sub2->additem(new Ea_Layout_Link('http://www.google.fr', 'Test 1.1.2'));
		$sub2->additem('Test 1.1.2');
		$sub2->additem('Test 1.1.3');
		
		$this->add($div = new Ea_Layout_Container);
		$div->addAttribute('class', 'main-container');
		$this->getPage()->addStyle('css/styles.css');
		
		$this->getPage()->setMainLayout($div);
		
	}
	
	public function actionIndex()
	{
	  $this->add('Hello world');
	}
}
