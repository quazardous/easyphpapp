<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Ui
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/List/Item.php';

/**
 * Menu item layout class.
 * @see Ea_Layout_Menu
 */
class Ea_Layout_Menu_Item extends Ea_Layout_List_Item
{	
	protected function preRender()
	{
		$render=parent::preRender();
		if ($this->getParent()->rootList()) {
		  $this->addAttribute('class', 'memu-root');
		}
		elseif ($this->hasChildren()) {
		  $this->addAttribute('class', 'has-children');
		}
		return $render;
	}
}