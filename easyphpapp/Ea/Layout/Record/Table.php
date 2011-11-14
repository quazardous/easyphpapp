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

require_once 'Ea/Layout/Grid.php';
require_once 'Ea/Layout/Record/Adapter/Interface.php';
require_once 'Ea/Layout/Record/Config/Modifier/Interface.php';
require_once 'Ea/Model/Layout.php';
require_once 'Ea/Layout/Grid/Exception.php';
require_once 'Ea/Layout/Grid/Populate/Adapter/Default.php';

/**
 * Table of records layout class.
 * 
 * @deprecated
 * @see Ea_Layout_Grid
 * 
 */
class Ea_Layout_Record_Table extends Ea_Layout_Grid
{
}