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

require_once 'Ea/Layout/List.php';

/**
 * Menu layout class.
 * Based on http://codebrewery.blogspot.com/2011/01/memu-simple-css-javascript-jquery-menu.html
 * You have to add the css contrib/memu/memu-0.1.css somewhow.
 */
class Ea_Layout_Menu extends Ea_Layout_List
{
	
  protected $_itemClass = 'Ea_Layout_Menu_Item';
  
	protected function preRender()
	{
		$render=parent::preRender();
		if ($this->rootList()) {
		  $this->addAttribute('class', 'memu');
		}
		return $render;
	}

	public function addItem($content=null, $config=null, $append=true, $class=null) {
	  require_once 'Ea/Layout/Link.php';
	  if (!($content instanceof Ea_Layout_Link)) {
	    $content = new Ea_Layout_Link('#', $content);
	  }
	  return parent::addItem($content, $config, $append, $class);
	}
}