<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Toolbox
 * @subpackage  Date
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */


/**
 * Date Toolbox Class.
 * 
 */
class Ea_Toolbox_Date
{
  static public function getTimestampFromFormat($value, $format) {
    $d=strptime($value, $format);
    if(!$d) return null;
    return mktime(
    $d['tm_hour'],
    $d['tm_min'],
    $d['tm_sec'],
    $d['tm_mon']+1,
    $d['tm_mday'],
    $d['tm_year']+1900);
  }
  
  static public function dbDatetime($ts = null) {
    if (!$ts) $ts = time();
    return date('Y-m-d H:i:s', $ts);
  }
  
  static public function dbDate($ts = null) {
    if (!$ts) $ts = time();
    return date('Y-m-d', $ts);
  }
}