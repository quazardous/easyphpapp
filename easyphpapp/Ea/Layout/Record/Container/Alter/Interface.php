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

/**
 * Interface to alter record container (cell or row).
 * @see Ea_Layout_Grid::addRecordContaineAlter()
 */
interface Ea_Layout_Record_Container_Alter_Interface
{
	/**
	 * Alter the container.
	 * 
	 * @param Ea_Layout_Container $container
	 * @param $record
	 * @param $i
	 * @param $spread
	 * @param $column
	 * @param $header
	 */
	public function alter(Ea_Layout_Container $container, $record, $i, $spread, $column = null, $header = false);
}
