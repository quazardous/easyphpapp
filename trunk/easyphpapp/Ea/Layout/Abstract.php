<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Base
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.1
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

/**
 * Abstract layout class.
 *
 * A layout is all thing that can be put on a page an rendered.
 * 
 */
abstract class Ea_Layout_Abstract
{
	/**
	 * Abstract layout constructor.
	 *
	 * @param array|Ea_Page $config
	 * 
	 *  - if associative array : config params, can be
	 * 
	 *  -- page : the page to which the layout belongs
	 * 
	 *  - if Ea_Page : the page to which the layout belongs
	 * 
	 */
	public function __construct($config=null)
	{
		if($config instanceof Ea_Page)
		{
			$config=array('page'=>$config);
		}
		if(is_array($config))
		{
			if(array_key_exists('page', $config))
			{
				$this->setPage($config['page']);
			}
		}
	}

	/**
	 * Parent container.
	 *
	 * @var Ea_Layout_Container
	 */
	protected $_parent=null;
	
	/**
	 * Set the parent container.
	 * 
	 * @param Ea_Layout_Container $parent
	 */
	public function setParent(Ea_Layout_Container $parent)
	{
		$this->_parent=$parent;
		$this->setPage($parent->getPage());
	}

	/**
	 * Return parent container
	 *
	 * @return Ea_Layout_Container
	 */
	public function getParent()
	{
		return $this->_parent;
	}
	
	/**
	 * The page to which the layout belongs.
	 *
	 * @var Ea_Page
	 */
	protected $_page=null;

	/**
	 * Set the page
	 *
	 * @param Ea_Page $page
	 * @see $_page
	 */
	public function setPage($page)
	{
		$this->_page=$page;
	}
	
	/**
	 * Return the page.
	 *
	 * @return Ea_Page
	 * @see $_page
	 */
	public function getPage()
	{
		if($this->_page) return $this->_page;
		if($this->getParent()) $this->_page=$this->getParent()->getPage(); 
		return $this->_page;
	}
	
	/**
	 * Escape methode inherited from Ea_Page.
	 * 
	 * Can be used to protect string at render.
	 *
	 * @param string $string
	 * @return string
	 *
	 * @see Ea_Page
	 */
	public function escape($string)
	{
		return $this->getPage()->escape($string);
	}
	
	/**
	 * Pre render validation stuff.
	 * It's highly recommended that preRender() call parent::preRender().
	 */
	public function preRender()
	{
		
	}
	
	/**
	 * Abstract render method.
	 * 
	 * Derived class must overload this method in order to render the layout.
	 * 
	 * It's highly recommended that abstract implements only render stuff.
	 * Special stuff like validaion shoud be put in preRender().
	 * So it's highly recommended that render() call preRender().
	 * 
	 * @see preRender()
	 * 
	 */
	abstract public function render();
}
?>