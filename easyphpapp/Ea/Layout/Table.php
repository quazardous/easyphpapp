<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Table
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Container.php';

/**
 * Table layout class.
 * This class let you create HTML table element.
 * 
 */
class Ea_Layout_Table extends Ea_Layout_Container
{
	protected $_tag='table';

	/**
	 * Table layout constructor.
	 * 
	 * @param array $config associative array of parameters
	 *  
	 */
	public function __construct($config=null)
	{
		parent::__construct(null, $config);
	}
	
	protected function _beforeAdd(Ea_Layout_Abstract $layout) {
	  if(($this->_assertRow)&&(!$layout instanceof Ea_Layout_Table_Row))
	  {
	    require_once 'Ea/Layout/Table/Exception.php';
	    throw new Ea_Layout_Table_Exception("not an instance of Ea_Layout_Table_Row");
	  }
	  $this->_currentRow=$layout;
	}
		
	/**
	 * Tells to check if children are strict row layout.
	 * 
	 * @param boolean $assert
	 */
	public function assertRow($assert=true)
	{
		$this->_assertRow=$assert;
	}
	
	protected $_assertRow=true;
	
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
	 * @param boolean $append
	 * @param string $class row class
	 * @return Ea_Layout_Table_Row
	 */
	public function addRow($config=null, $append=true, $class='Ea_Layout_Table_Row')
	{
		require_once 'Zend/Loader.php';
		Zend_Loader::loadClass($class);
		$row=new $class($config);
		$this->add($row, $append);
		return $row;
	}
	
	/**
	 * Add new cell to the current row with content.
	 * 
	 * @param mixed $content to add to the new cell if given
	 * @param mixed $config pass it to the cell constructor
	 * @param boolean $append
	 * @param string $class cell class
	 * @return Ea_Layout_Table_Cell
	 */
	public function addCell($content=null, $config=null, $append=true, $class='Ea_Layout_Table_Cell')
	{
		if(!$this->_currentRow) $this->addRow();
		return $this->_currentRow->addCell($content, $config, $append, $class);
	}

	/**
	 * Add new header cell to the current row with content.
	 * 
	 * @param mixed $content to add to the new cell if given
	 * @param mixed $config pass it to the header cell constructor
	 * @param boolean $append
	 * @param string $class header cell class
	 * @return Ea_Layout_Table_Header
	 */
	public function addHeader($content=null, $config=null, $append=true, $class='Ea_Layout_Table_Header')
	{
		if(!$this->_currentRow) $this->addRow();
		return $this->_currentRow->addHeader($content, $config, $append, $class);
	}
}
