<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Form
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.2.4.20081017
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Input/Abstract.php';

/**
 * Textarea input layout class.
 */
class Ea_Layout_Input_Textarea extends Ea_Layout_Input_Abstract
{
	protected $_tag='textarea';
	
	public function render()
	{
		echo '<';
		echo $this->_tag;
		$this->renderAttributes();
		echo '>';

		echo $this->escape($this->getValue());

		echo '</';
		echo $this->_tag;
		echo '>';
	}
}
?>