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
  /**
   * Sprite icon (famfamfam like)
   * @var string
   */
  protected $_icon = null;
  
  public function setIcon($icon) {
    $this->_icon = $icon;
  }
  
  /**
   * @var Ea_Layout_Link
   */
  protected $_link = null;
  
	protected function preRender()
	{
		$render=parent::preRender();
		if ($this->getParent()->rootList()) {
		  $this->addAttribute('class', 'memu-root');
		}
		elseif ($this->hasChildren()) {
		  $this->addAttribute('class', 'has-children');
		}
		
		if ($this->_icon) {
		  $this->_link->add($div=new Ea_Layout_Container, false);
		  $div->addAttribute('class', 'memu-icon');
		  $div->addAttribute('class', 'sprite-' . $this->_icon);
		}
		
		return $render;
	}
	
	public function add($content, $append=true) {
	  require_once 'Ea/Layout/Link.php';
	  if ($content instanceof Ea_Layout_Link && !$this->_link) {
	    $this->_link = $content;
	  }
	  parent::add($content, $append);
	}
	
}

