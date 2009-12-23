<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Table
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.3.4-20090204
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

/**
 * Interface to modify config of cell or row depending on record.
 * @see Ea_Layout_Grid::addRecordConfigRowModifier()
 * @see Ea_Layout_Grid::addRecordConfigCellModifier()
 */
interface Ea_Layout_Record_Config_Modifier_Interface
{
	/**
	 * Modify the config.
	 * 
	 * @param $config
	 * @param $record
	 * @param $i
	 * @param $column
	 * @return array modified config
	 */
	public function modify($config, $record, $i, $column=null);
}
