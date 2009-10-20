<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Table
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.4.0-20091015
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

/**
 * Adapter used to manage the row and cell population.
 * @see Ea_Layout_Record_Table
 */
interface Ea_Layout_Record_Table_Populate_Adapter_Interface
{
	
	/**
	 * Acknowledge the table.
	 * 
	 * @param Ea_Layout_Record_Table $table
	 */
	public function acknowledge(Ea_Layout_Record_Table $table);
	
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
	 * @param string $colName
	 * @return Ea_Layout_Table_Row
	 */
	public function getColRow($colName, $records);

}