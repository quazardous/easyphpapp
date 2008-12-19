<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     examples
 * @subpackage  bacth
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.3.3-20081212
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Batch/Abstract.php';

/**
 * Little test batch class.
 * 
 */
class Batch_Test extends Ea_Batch_Abstract
{
	protected $_batch='test';
	
	protected $_params=array(
		 'param1'=>array(
		 	'value'=>null,
		 	'shortop'=>'p', // for setParamsFromGetopt()
		 	'longop'=>'param1', // for setParamsFromGetopt()
		 	'boolean'=>false, // just a toggle option with no value
			'optional'=>false),
	);
	
	protected function start()
	{
		echo "Hello World\n";
		echo "param1=".$this->getParam('param1')."\n";
		
		// your stuff
		// you can stop the bacth with $this->stop(9, "why ?")
		// the code will be returned by run().
	}
}