<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Form
 * @author      berlioz [$Author$]
 * @version     $Id$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Input/Date.php';

/**
 * Date with DatePicker input layout class.
 */
class Ea_Layout_Input_DatePicker extends Ea_Layout_Input_Date
{
 
  protected function preRender()
  {
    if(!parent::preRender()) return false;
    $id=$this->getAttributeId();
    $script="$('#{$id}').datepicker();";
    require_once 'Ea/Layout/Script.php';
    $script=new Ea_Layout_Script($script, true);
    $this->addScript($script);
    return true;
  }
  
}