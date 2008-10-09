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
	public function setPage(Ea_Page $page)
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
	 * Abstract render method.
	 * 
	 * Derived class must overload this method in order to render the layout.
	 * 
	 */
	abstract public function render();
}
?>