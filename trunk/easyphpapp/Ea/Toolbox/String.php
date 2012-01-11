<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Toolbox
 * @subpackage  String
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */


/**
 * tring Toolbox Class.
 * 
 */
class Ea_Toolbox_String
{
  static public function unaccent($text) {
    static $search, $replace;
    if (!$search) {
      $search = $replace = array();
      // Get the HTML entities table into an array
      $trans = get_html_translation_table(HTML_ENTITIES);
      // Go through the entity mappings one-by-one
      foreach ($trans as $literal => $entity) {
        // Make sure we don't process any other characters
        // such as fractions, quotes etc:
        if (ord($literal) >= 192) {
          // Get the accented form of the letter
          $search[] = utf8_encode($literal);
          // Get e.g. 'E' from the string '&Eacute'
          $replace[] = $entity[1];
        }
      }
    }
    return str_replace($search, $replace, $text);
  }
}