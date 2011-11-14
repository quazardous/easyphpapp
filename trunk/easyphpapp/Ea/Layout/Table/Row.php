<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Table
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id:$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Container.php';

/**
 * Table row layout class.
 * 
 * @see Ea_Layout_Table
 */
class Ea_Layout_Table_Row extends Ea_Layout_Container
{
	protected $_tag='tr';

	/**
	 * Table row layout constructor.
	 * 
	 * @param array $config associative array of parameters
	 *  
	 */
	public function __construct($config=null)
	{
		parent::__construct(null, $config);
	}
	
	/**
	 * Add content to row.
	 * Accept only cells.
	 *
	 */
	public function add($content, $append=true)
	{
		if(is_array($content)&&(!is_object($content)))
		{
			foreach($content as $item)
			{
				$this->add($item, $append);
			}
			return;
		}
		if(!($content instanceof Ea_Layout_Table_Cell))
		{
			require_once 'Ea/Layout/Table/Exception.php';
			throw new Ea_Layout_Table_Exception("not an instance of Ea_Layout_Table_Cell");
		}
		parent::add($content, $append);
	}

	/**
	 * Shortcut method to add a cell with content.
	 * 
	 * @param Ea_Layout_Abstract|string|array $content
	 * @param array $config config for the cell constructor
	 * @param boolean $append
	 * @param string $class cell class
	 * 
	 * @return Ea_Layout_Table_Cell the new cell
	 */
	public function addCell($content=null, $config=null, $append=true, $class='Ea_Layout_Table_Cell')
	{
		Zend_Loader::loadClass($class);
		$cell=new $class($config);
		if(!$cell instanceof Ea_Layout_Table_Cell)
		{
			require_once 'Ea/Layout/Table/Exception.php';
			throw new Ea_Layout_Table_Exception("$class not an instance of Ea_Layout_Table_Cell");
		}
		$this->add($cell, $append);
		if($content) $cell->add($content);
		return $cell;
	}
	
	/**
	 * Shortcut method to add a header cell with content.
	 * 
	 * @param Ea_Layout_Abstract|string|array $content
	 * @param array $config config for the cell constructor
	 * @param boolean $append
	 * @param string $class header cell class
	 * 
	 * @return Ea_Layout_Table_Header the new cell
	 */
	public function addHeader($content=null, $config=null, $append=true, $class='Ea_Layout_Table_Header')
	{
		Zend_Loader::loadClass($class);
		$cell=new $class($config);
		if(!$cell instanceof Ea_Layout_Table_Header)
		{
			require_once 'Ea/Layout/Table/Exception.php';
			throw new Ea_Layout_Table_Exception("$class not an instance of Ea_Layout_Table_Header");
		}
		$this->add($cell, $append);
		if($content) $cell->add($content);
		return $cell;
	}

}
