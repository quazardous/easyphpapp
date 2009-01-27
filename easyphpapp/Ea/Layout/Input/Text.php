<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Form
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.3.4-20090127
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Input/Abstract.php';

/**
 * Text input layout class.
 */
class Ea_Layout_Input_Text extends Ea_Layout_Input_Abstract
{
	protected $_type='text';
	
	public function __construct($id=null, $value=null, $size=null, $maxlength=null, $config=null)
	{
		parent::__construct($id, $value, $config);
		if($size!==null) $this->setAttribute('size', $size);
		if($maxlength!==null) $this->setAttribute('maxlength', $maxlength);
	}
}
?>