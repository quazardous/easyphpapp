<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     examples
 * @subpackage  charsetapp
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.5.0-20101011
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 * @filesource
 */

require_once 'Ea/Module/Abstract.php';

/**
 * My basic module.
 *
 */
class Module_Index extends Ea_Module_Abstract
{
	public function init()
	{
		// set the page title
		$this->getPage()->setTitle('Charset : éà€ù$!!<@#');
	}
	
	public function actionIndex()
	{
		// add the text 'Hello World' to the page
		
	  $this->add('Charset : éà€ù$!!<@#');
	}
}
