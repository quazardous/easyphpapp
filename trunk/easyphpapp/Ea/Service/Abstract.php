<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Service
 * @subpackage  core
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.4.6-20101007
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Encoding/Abstract.php';

/**
 * Abstract service class.
 * A service is typically a call to an external web service.
 * This class handles connexion stuff, etc
 *
 */
class Ea_Service_Abstract extends Ea_Encoding_Abstract
{
	/**
	 * Default HTTP adapter.
	 * 
	 * @var array
	 * @see Zend_Http_Client::setConfig()
	 */
	static protected $_defaultHttpAdapterConfig=array();
	
	/**
	 * Set the default HTTP adapter.
	 * 
	 * @param Zend_Http_Client_Adapter_Interface|string $adapter
	 */
	static public function setDefaultHttpAdapter($adapter, $config=array())
	{
		$config['adapter']=$adapter;
		self::$_defaultHttpAdapterConfig=$config;
	}
	
	/**
	 * HTTP Client.
	 * 
	 * @var Zend_Http_Client
	 */
	protected $_httpClient=null;
	
	/**
	 * Return HTTP Client.
	 * 
	 * @return Zend_Http_Client
	 */
	public function getHttpClient()
	{
		if($this->_httpClient)
		{
			return $this->_httpClient;
		}
		require_once 'Zend/Http/Client.php';
		$this->_httpClient=new Zend_Http_Client(null, self::$_defaultHttpAdapterConfig);
		return $this->_httpClient;
	}
	
	const method_GET  = 'GET';
	const method_POST = 'POST';
	
	/**
	 * Add description here...
	 * 
	 * @var Zend_Http_Response
	 */
	protected $_httpResponse=null;
	
	/**
	 * Set request parameter.
	 * 
	 * @param array $params
	 * @param string $method
	 * @param string $encoding 
	 * 
	 * @return Zend_Http_Client
	 */
	public function setRequestParameters(array $params, $method=self::method_GET, $encoding=null)
	{
		if(!$encoding) $encoding=$this->getRequestEncoding();
		if($encoding!=$this->getInternalEncoding())
		{
			foreach($params as $k=>&$v)
			{
				$v=$this->encode($v);
			}
		}
		$client=$this->getHttpClient();
		switch($method)
		{
			case self::method_POST:
				$client->setParameterPost($params);
				break;
			default:
				$client->setParameterGet($params);
		}
	}
	
	/**
	 * Prepare the request.
	 * 
	 * @param string $uri
	 * @param array $params
	 * @param string $method
	 * @param string $encoding 
	 */
	public function prepareRequest($uri, array $params=array(), $method=self::method_GET, $encoding=null)
	{
		$this->_httpResponse=null;
		$client=$this->getHttpClient();
		$client->resetParameters();
		$client->setUri($this->encode($uri, $encoding));
		$client->setMethod($method);
		$this->setRequestParameters($params, $method, $encoding);
		return $client;
	}
	
	/**
	 * Request the prepared uri or the given one.
	 * 
	 * @param string $uri if not null prepare the request
	 * @param array $params
	 * @param string $method
	 * @param string $encoding
	 * @return boolean
	 */
	public function request($uri=null, array $params=array(), $method=self::method_GET, $encoding=null)
	{
		if($uri)
		{
			$this->prepareRequest($uri, $params, $method, $encoding);
		}
		$client=$this->getHttpClient();
		
		$this->_httpResponse=$client->request();
		
		return $this->getHttpResponse()->isSuccessful();
	}
	
	/**
	 * Return the http response.
	 * 
	 * @return Zend_Http_Response
	 */
	public function getHttpResponse()
	{
		return $this->_httpResponse;
	}
	
	/**
	 * Return response body.
	 * 
	 * @param string $encoding encoding of the response
	 * @return string
	 */
	public function getHttpResponseBody($encoding=null)
	{
		return $this->decode($this->getHttpResponse()->getBody(), $encoding);
	}
	
	static public function setDefaultRequestEncoding($encoding)
	{
		self::setDefaultTargetEncoding($encoding);
	}
	
	static public function getDefaultRequestEncoding()
	{
		return self::getDefaultTargetEncoding();
	}
	
	/**
	 * Set request/response encoding.
	 * 
	 * @param string $request
	 */
	public function setRequestEncoding($encoding)
	{
		$this->setTargetEncoding($encoding);
	}
	
	public function getRequestEncoding()
	{
		return $this->getTargetEncoding();
	}
		
	/**
	 * Return the response body as json.
	 * json_decode always uses UTF-8
	 * 
	 * @param string $encoding encoding of the response
	 * @return array json
	 */
	public function getHttpResponseJSON($encoding=null)
	{
		if(!$encoding) $encoding=$this->getInternalEncoding();
		$json=json_decode($this->utf8Encode($this->getHttpResponse()->getBody(), $encoding));
		if(strtoupper($encoding)=='UTF-8'||!$encoding) return $json;
		$this->recursiveWalkEncode($json, array($this, 'utf8Decode'), $encoding);
		return $json;
	}
	
	protected function recursiveWalkEncode(&$arr, $callback, $encoding)
	{
		if(is_array($arr)||$arr instanceof Iterator||$arr instanceof stdClass)
		{
			foreach($arr as &$item)
			{
				$this->recursiveWalkEncode($item, $callback, $encoding);
			}
		}
		else $arr=call_user_func($callback, $arr, $encoding);
	}

	/**
	 * Return the response body as xml.
	 * 
	 * @param string $encoding encoding of the response
	 * @return Ea_Xml_Element
	 */
	public function getHttpResponseXML($encoding=null)
	{
		if(!$encoding) $encoding=$this->getRequestEncoding();
		// try to load the response in XML with UTF-8 (used internaly by Ea_Xml_Element)
		require_once 'Ea/Xml/Element.php';
		$xml=Ea_Xml_Element::load_string($this->getHttpResponse()->getBody(), null, null, null, $encoding);
		// now set the input encoding
		$xml->setInternalEncoding($this->getInternalEncoding());
		return $xml;
	}
	
}