<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Table
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.1
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Container.php';
require_once 'Ea/Layout/Table/Cell.php';
require_once 'Ea/Layout/Table/Header.php';

/**
 * Table row layout class.
 * 
 * @see Ea_Layout_Table
 */
class Ea_Layout_Table_Row extends Ea_Layout_Container
{
	protected $_tag='tr';

	/**
	 * Add content to row.
	 * Accept only cells.
	 *
	 */
	public function add($content)
	{
		if(!($content instanceof Ea_Layout_Table_Cell))
		{
			throw new Ea_Layout_Table_Exception("not an instance of Ea_Layout_Table_Cell");
		}
		parent::add($content);
	}

	/**
	 * Shortcut method to add a cell with content.
	 * 
	 * @param Ea_Layout_Abstract|string|array $content
	 * @param array $config config for the cell constructor
	 * 
	 * @return Ea_Layout_Table_Cell the new cell
	 */
	public function addCell($content=null, $config=null)
	{
		$cell=new Ea_Layout_Table_Cell($config);
		$this->add($cell);
		if($content) $cell->add($content);
		return $cell;
	}
	
	/**
	 * Shortcut method to add a header cell with content.
	 * 
	 * @param Ea_Layout_Abstract|string|array $content
	 * @param array $config config for the cell constructor
	 * 
	 * @return Ea_Layout_Table_Header the new cell
	 */
	public function addHeader($content=null, $config=null)
	{
		$cell=new Ea_Layout_Table_Header($config);
		$this->add($cell);
		if($content) $cell->add($content);
		return $cell;
	}

}

?>