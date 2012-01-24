<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Model
 * @subpackage  Layout
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Record/Container/Alter/Interface.php';

/**
 * Alter to add colum and type class from model.
 *
 */
class Ea_Model_Layout_Record_Container_Alter_Default implements Ea_Layout_Record_Container_Alter_Interface
{
  /**
   * @var Ea_Model_Layout
   */
  protected $_model;
  
	public function __construct(Ea_Model_Layout $model)
	{
		$this->_model=$model;
	}
	
	public function alter(Ea_Layout_Container $container, $record, $i, $spread, $column = null, $header = false)
	{
		if ($column!==null) {
		  $container->addAttribute('class', 'col-' . $column);
		  if (!$header) {
  		  if ($type = $this->_model->getColumnType($column)) {
  		    $container->addAttribute('class', 'type-' . $type);
  		  }
		  }
		}
		else {
  		if ($i!==null) {
  		   $container->addAttribute('class', $i%2==0 ? 'even' : 'odd');
  		}
		}
	}
}
