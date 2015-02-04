<?php
App::uses('Component', 'Controller');
App::uses('Xml', 'Utility');
/**
 * Finding Component
 *
 * @author Rafael F Queiroz <rafaelfqf@gmail.com>
 */
class FindingComponent extends Component { 


	/**
	 * Key
	 *
	 * @var string
	 */
	protected $key = null;

	/**
	 * Version
	 *
	 * @var string
	 */
	protected $version = '1.0.0';

	/**
	 * Endpoint
	 *
	 * @var string
	 */
	protected $url = "http://svcs.ebay.com/services/search/FindingService/v1";

	/**
	 * Constructor
	 *
	 * @param ComponentCollection $collection
	 * @param array $settings
	 * @return GooglePlacesComponent
	 */
	public function __construct(ComponentCollection $collection, $settings = array()) {
		parent::__construct($collection, $settings);
		$this->settings = array_merge($this->settings, $settings);
	}

	/**
	 * Initialize callback
	 *
	 * @param Controller $controller
	 * @param array $settings
	 * @return void
	 */
	public function initialize(Controller $controller, $settings = array()) {
		$this->key = Configure::read('eBay.key');
		if (!$this->key) {
			throw new CakeException ("You must set eBay.key configuration");
		}
	}

	/**
	 * Find Items By Keywords 
	 * Returns items based on a keyword query and returns details for matching items.
	 *
	 * @see http://developer.ebay.com/Devzone/finding/CallRef/findItemsByKeywords.html
	 * @param string $keywords
	 * @param array  $params
	 * @return SimpleXMLElement
	 */
	public function findItemsByKeywords($keywords, $params=array()) {
		$params = array_merge(array('keywords' => $keywords), $params);
		return $this->_search('findItemsByKeywords', $params);
	}

	/**
	 * Search method
	 *
	 * @param string $method
	 * @param array  $params
	 * @return SimpleXMLElement
	 */
	protected function _search($method, $params) {
		$params = array_merge(array(
			'SECURITY-APPNAME' => $this->key,
			'OPERATION-NAME'  => $method,
			'SERVICE-VERSION' => $this->version,
			'RESPONSE-DATA' => 'XML'
		), $params);

		$request = $this->_makeRequest($this->url, $params);
		if ($request) {
			return $request;
		}

		return false;
	}

	/**
	 * Make a Request.
	 *
	 * @var string $name
	 * @var array $params
	 * @return SimpleXMLElement
	 */
	protected function _makeRequest($url, $params = array()) {
		return Xml::build($url . "?" . http_build_query($params));
	}

}
