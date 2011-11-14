<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Base
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id:$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Element/Abstract.php';

/**
 * Single tag element layout class.
 * 
 * In HTML : a single tag (ie: <br />).
 * 
 * Example :
 * <code>
 * <?php
 *  // a simple <br /> tag
 * 	$br=new Ea_Layout_Single(array('tag'=>'br'));
 * ?>
 * </code>
 */
class Ea_Layout_Single extends Ea_Layout_Element_Abstract
{
	/**
	 * Single tag layout constructor.
	 * 
	 * @param array $config associative array of parameters
	 * @param array $config associative array of parameters
	 * 
	 * @see addCallback()
	 * 
	 */
	public function __construct($tag=null, $config=null)
	{
		parent::__construct($config);
		if($tag) $this->setTag($tag);
	}
	
	protected function render()
	{
		echo '<';
		echo $this->_tag;
		$this->renderAttributes();
		echo " />";
	}
}
