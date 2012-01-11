<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Service
 * @subpackage  GSearch
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Service/Abstract.php';

/**
 * Google Search Parser service.
 * 
 */
class Ea_Service_GSearch_Parser extends Ea_Service_Abstract
{
	
	protected $_targetEncoding='UTF-8';

	public function __construct($country = null, $baseUri = null)
	{
	  if ($baseUri) $this->setBaseUri($baseUri);
		$this->setCountry($country);
	}
	
	protected $_baseUri='http://www.google.com/search';
	
	public function setBaseUri($baseUri) {
	  $this->_baseUri = $baseUri;
	}
	
	/**
	 * @var string
	 */
	protected $_country=null;
	
	public function setCountry($country)
	{
		$this->_country=$country;
	}
	
	/**
	 * Geocode given address with Google Geocoder.
	 * 
	 * @param string $address
	 * @return Ea_Service_GMap_GeocoderV3_ResultSet
	 */
	public function search($q, $start = 0, $num = 10)
	{
	  $params = array(
			'q' => $q,
			'num' => $num,
		);
	  
	  if ($this->_country) {
	    // not sur of this
	    $params['gl'] = $this->_country;
	  }
	  
	  if ($start) {
	    $params['start'] = $start;
	  }
	  
		$ret=$this->request($this->_baseUri, $params);
		if(!$ret) return false;
		$doc = new DOMDocument();
		@$doc->loadHTML($this->getHttpResponseBody());
		
		$res = $doc->getElementById('ires');
		$xpath = new DOMXpath($doc);
		$elements = $xpath->query("./ol/li", $res);
		
		$results = array();
		if ($elements->length) {
		  $i = $start;
		  foreach ($elements as $element) {
		    $la = $element->getElementsByTagName('a');
		    
		    foreach ($la as $a) {
		      $title = strtolower(trim($a->textContent));
		      $href = $a->getAttribute('href');
		      if (!$title) continue;
		      if ($title == 'cached' || $title == 'similar') continue;
		      
		      if (!preg_match(',^(https?|ftp)://,i', $href)) continue;
		      
		      $i++;
		      $results[] = array(
		        'title' => $a->textContent,
		        'link' => $href,
		        'pos' => $i,
		      );
		    }
		    
		  }
		}
		
		return $results;
	}
	
}