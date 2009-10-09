<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Form
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.3.8-20091002
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Input/Abstract.php';

/**
 * Submit input layout class.
 */
class Ea_Layout_Input_Submit extends Ea_Layout_Input_Abstract
{
	protected $_type='submit';
	
	protected $_submitCallbacks=array();
	
	/**
	 * Add submit callback to the button.
	 * 
	 * @param callback $callback
	 *  the callback will recieve one argument : the form object.
	 * @param boolean $module : by default $callback is one of the module method, else it's a classic php callback
	 *  be aware that callbacks can be serialized.
	 */
	public function addSubmitCallback($callback, $module=true)
	{
		$this->_submitCallbacks[]=array('callback'=>$callback, 'module'=>$module);
	}
	
	public function __sleep()
    {
        return array_merge(parent::__sleep(), array('_submitCallbacks'));
    }
	
	public function __construct($id=null, $value=null, $callback=null, $config=null)
	{
		parent::__construct($id, $value, $config);
		if($callback)$this->addSubmitCallback($callback);
	}
}
?>