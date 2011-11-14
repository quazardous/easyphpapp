<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Table
 * @author      berlioz [$Author]
 * @version     $Id:$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Grid.php';

/**
 * Adapter used to manage the row and cell population.
 * @see Ea_Layout_Grid
 */
interface Ea_Layout_Grid_Populate_Adapter_Interface
{
	
	/**
	 * Acknowledge the table.
	 * 
	 * @param Ea_Layout_Grid $table
	 */
	public function acknowledge(Ea_Layout_Grid $table);
	
	/**
	 * Return the row for the header if spread is vertical
	 * 
	 * @param array $columns the columns definition
	 * @return Ea_Layout_Table_Row
	 */
	public function getHeaderRow();
	
	/**
	 * Return the row for the record if spread is vertical
	 * 
	 * @param mixed $record the data record
	 * @param int $i
	 * @return Ea_Layout_Table_Row
	 */
	public function getRecordRow($record, $i);
	
	/**
	 * Return the row if spread is horizontal
	 * 
	 * @param string $idCol
	 * @return Ea_Layout_Table_Row
	 */
	public function getColRow($idCol, $records);

	/**
	 * Return the record cell.
	 * 
	 * @param string $idCol
	 * @param mixed $record
	 * @param int $i
	 * @param 'vertical'|'horizontal' $spread
	 * @return Ea_Layout_Table_Cell
	 */
	public function getRecordCell($idCol, $record, $i, $spread);
	
}