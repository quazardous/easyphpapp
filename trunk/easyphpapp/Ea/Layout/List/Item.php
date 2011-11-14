<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  List
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id:$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Container.php';

/**
 * List item layout class.
 * 
 * @see Ea_Layout_List
 */
class Ea_Layout_List_Item extends Ea_Layout_Container
{
	protected $_tag='li';
	
	/**
	 * Table cell layout constructor.
	 * 
	 * @param array $config associative array of parameters
	 *  
	 */
	public function __construct($config=null)
	{
		parent::__construct(null, $config);
	}
}

