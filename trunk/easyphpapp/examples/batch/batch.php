<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     examples
 * @subpackage  batch
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id:$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 * @filesource
 */

// Little batch laucher

// don't forget to set your include path
require '../conf/config.php';

require_once 'Batch/Test.php';

$batch=new Batch_Test();

if(isset($_SERVER['REQUEST_METHOD']))
{
	// GET
	$batch->setParamsFromArray($_GET);
}
else
{
	// argv
	$batch->setParamsFromGetopt();
}

exit($batch->run());
// run returns 0 or the code passed to stop().
