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
require_once 'Ea/Layout/Table/Row.php';
require_once 'Ea/Layout/Table/Cell.php';

/**
 * Table layout class.
 * This class let you create HTML table element.
 * 
 */
class Ea_Layout_Table extends Ea_Layout_Container
{
	protected $_tag='table';

	/**
	 * Enter description here...
	 * 
	 * @param $content
	 * @return unknown_type
	 * @see Ea/Layout/Ea_Layout_Container#add()
	 */
	public function add($content)
	{
		if(!($content instanceof Ea_Layout_Table_Row))
		{
			throw new Ea_Layout_Table_Exception("not an instance of Ea_Layout_Table_Row");
		}
		parent::add($content);
	}
	
	/**
	 * Current row.
	 *
	 * @var Ea_Layout_Table_Row
	 */
	protected $_currentRow=null;
	
	/**
	 * Add a new row.
	 *
	 * @param mixed $config pass it to the row constructor
	 * @return Ea_Layout_Table_Row
	 */
	public function addRow($config=null)
	{
		$this->_currentRow=new Ea_Layout_Table_Row($config);
		$this->add($this->_currentRow);
		return $this->_currentRow;
	}
	
	/**
	 * Add new cell to the current row with content.
	 * 
	 * @param mixed $content to add to the new cell if given
	 * @param mixed $config pass it to the cell constructor
	 * @return Ea_Layout_Table_Cell
	 */
	public function addCell($content=null, $config=null)
	{
		if(!$this->_currentRow)
		{
			throw new Ea_Layout_Table_Exception("no current row");
		}
		return $this->_currentRow->addCell($content, $config);
	}

	/**
	 * Add new header cell to the current row with content.
	 * 
	 * @param mixed $content to add to the new cell if given
	 * @param mixed $config pass it to the header cell constructor
	 * @return Ea_Layout_Table_Header
	 */
	public function addHeader($content=null, $config=null)
	{
		if(!$this->_currentRow)
		{
			throw new Ea_Layout_Table_Exception("no current row");
		}
		return $this->_currentRow->addHeader($content, $config);
	}
}

?>