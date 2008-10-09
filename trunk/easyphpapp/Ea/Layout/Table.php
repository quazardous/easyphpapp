<?php
require_once 'Ea/Layout/Container.php';
require_once 'Ea/Layout/Table/Row.php';
require_once 'Ea/Layout/Table/Cell.php';

class Ea_Layout_Table extends Ea_Layout_Container
{
	protected $_tag='table';
	public function add($content)
	{
		if(!($content instanceof Ea_Layout_Table_Row))
		{
			throw new Ea_Layout_Table_Exception("not an instance of Ea_Layout_Table_Row");
		}
		parent::add($content);
	}
	
	/**
	 * Current row
	 *
	 * @var Ea_Layout_Table_Row
	 */
	protected $_currentRow=null;
	
	/**
	 * Add new row
	 *
	 * @param mixed $config
	 * @return Ea_Layout_Table_Row
	 */
	public function addRow($config=null)
	{
		$this->_currentRow=new Ea_Layout_Table_Row($config);
		$this->add($this->_currentRow);
		return $this->_currentRow;
	}
	
	public function addCell($content=null, $config=null)
	{
		if(!$this->_currentRow)
		{
			throw new Ea_Layout_Table_Exception("no current row");
		}
		return $this->_currentRow->addCell($content, $config);
	}

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