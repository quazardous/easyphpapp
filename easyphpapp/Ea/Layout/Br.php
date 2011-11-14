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

require_once 'Ea/Layout/Single.php';

/**
 * Br element layout class.
 * 
 */
class Ea_Layout_Br extends Ea_Layout_Single
{
	protected $_tag='br';
	
	/**
	 * Single tag layout constructor.
	 * 
	 * @see addCallback()
	 * 
	 */
	public function __construct($config=null)
	{
		parent::__construct(null, $config);
	}
}
