<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Form
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Input/Text.php';

/**
 * Autocomplete input layout class.
 */
class Ea_Layout_Input_Autocomplete extends Ea_Layout_Input_Text
{
  
  public function __construct($id=null, $value=null, $autocomplete = null, $size=null, $maxlength=null, $config=null)
  {
    parent::__construct($id, $value, $size, $maxlength, $config);
    if($autocomplete!==null) $this->autocomplete($autocomplete);
  }
  
  protected $_autocomplete = array();
  
  public function autocomplete($options) {
    if (!is_array($options) || !isset($options['source'])) {
      $options2['source'] = $options;
      unset($options);
      $options = $options2;
      unset($options2);
    }
    $this->_autocomplete = $options;
  }
  
  protected function render()
  {
    parent::render();
    $id=$this->getAttributeId();
    $pValues = array();
    $script = '';
    foreach (array('source') as $param) {
      if ($value = $this->getAutocompleteParam($param)) {
        $pValues[] = $value;
      }
    }
    $script.="$('#{$id}').autocomplete({" . implode(', ', $pValues)." });";
    require_once 'Ea/Layout/Script.php';
    $script=new Ea_Layout_Script($script, true);
    $script->display();
  }
  
  protected function getAutocompleteParam($param) {
    switch ($param) {
      case 'source':
        if (!isset($this->_autocomplete[$param])) return '';
        if (!$this->_autocomplete[$param]) return '';

        if ($this->_autocomplete[$param] instanceof Ea_Route) {
          return $param . ': ' . '"' . addcslashes($this->_autocomplete[$param]->url(), '"') . '"';
        }
        // no break !
      default:
        if (!isset($this->_autocomplete[$param])) return '';
        if (!$this->_autocomplete[$param]) return '';
        
        if (is_array($this->_autocomplete[$param])) {
          $items = array();
          if (array_values($this->_autocomplete[$param]) !== $this->_autocomplete[$param]) {
          	foreach ($this->_autocomplete[$param] as $key => $item) {
          	  if (!is_string($key)) $key = $item;
          	  $items[] = '{ value: "' . addcslashes($key, '"') . '", label: "' . addcslashes($item, '"') . '" }';
          	}
          	return  $param . ': ' . '[' . implode(', ', $items) . ']';
          }
          else {
            foreach ($this->_autocomplete[$param] as $item) {
              $items[] = '"' . addcslashes($item, '"') . '"';
            }
            return  $param . ': ' . '[' . implode(', ', $items) . ']';
          }
        }
        if (is_string($this->_autocomplete[$param])) return $param . ': ' . '"' . addcslashes($this->_autocomplete[$param], '"') . '"';
        return $param . ': ' . '"' . $this->_autocomplete[$param] . '"';
    }
  }
}