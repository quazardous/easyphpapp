<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Form
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.4.4-20100317
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Input/JQueryUi/DatePicker.php';

/**
 * Date with DatePicker input layout class.
 */
class Ea_Layout_Input_DatePicker extends Ea_Layout_Input_JQueryUi_DatePicker
{
	protected function render()
	{
		parent::render();
		$id=$this->getAttributeId();
		$script="$('#{$id}').datepicker();";
		$script=new Ea_Layout_Script($script, true);
		$script->display();
	}
}