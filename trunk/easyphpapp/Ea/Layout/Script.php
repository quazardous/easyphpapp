<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Base
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.2.5.20081020
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Single.php';

/**
 * Textarea input layout class.
 */
class Ea_Layout_Script extends Ea_Layout_Single
{
	protected $_tag='script';

	/**
	 * Ea_Layout_Input_Abstract constructor.
	 *
	 * @param string|array(string) $id the id of the input
	 * @param string|numeric $value
	 * @param array $config
	 * 
	 * @see $_id
	 */
	public function __construct($src, $script, $type='text/javascript', $config=null)
	{
		parent::__construct(null, $config);
		$this->setScript($script);
		if($src)$this->setAttribute('src', $src);
		$this->setAttribute('type', $type);
	} 
	
	protected $_script=null;
	public function setScript($script)
	{
		$this->_script=$script;
	}
	public function getScript()
	{
		return $this->_script;
	}
		
	public function render()
	{
		echo '<';
		echo $this->_tag;
		$this->renderAttributes();
		echo '>';

		echo $this->escape($this->getScript());

		echo '</';
		echo $this->_tag;
		echo '>';
	}
}
?>