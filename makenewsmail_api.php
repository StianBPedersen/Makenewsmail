<?php
class MakeNewsmail_api{
	protected $url;
	protected $username;
	protected $password;
	protected $options;

	public function __construct ($username, $password){
		$this->username	= $username;
		$this->password	= $password;
	}

	//Subscriberlists handling
	public function get_lists(){
		$this->url =  "https://api.makenewsmail.com/V1/lists/lists.xml";
		$this->get();
	}

	public function create_list($params){
		$xmldoc = '
		<?xml version="1.0" encoding="utf-8"?>
		<subscriberlists>
			<create>
				'. $this->to_xml($params) .'
			</create>
		</subscriberlists>
		';

		$this->url = "https://api.makenewsmail.com/V1/lists/lists.xml";
		$this->post($xmldoc);
	}

	//Subscriber handling
	public function subscribe($list_id, $params){
		$xmldoc = '
		<?xml version="1.0" encoding="utf-8"?>
		<subscribers>
			<subscribe>
				'. $this->to_xml($params) .'
			</subscribe>
		</subscribers>
		';

		$this->url =  "https://api.makenewsmail.com/V1/subscribers/$list_id.xml";
		$this->post($xmldoc);
	}

	public function unsubscribe($list_id, $params){
		$xmldoc = '
		<?xml version="1.0" encoding="utf-8"?>
		<subscribers>
			<unsubscribe>
				'. $this->to_xml($params) .'
			</unsubscribe>
		</subscribers>
		';

		$this->url =  "https://api.makenewsmail.com/V1/subscribers/$list_id.xml";
		$this->post($xmldoc);
	}




	//Private methods
	protected function post($requestBody){
		$requestBody = preg_replace('/\s+</', '<', $requestBody);

		$this->options = array(	
			CURLOPT_URL 					=> $this->url,
			CURLOPT_HTTPHEADER 		=> array('Content-Type: text/xml; charset=utf-8'),
			CURLOPT_USERPWD 			=> $this->username . ":" . $this->password,
			CURLOPT_POST					=> 1,
			CURLOPT_POSTFIELDS 		=> $requestBody,
			CURLOPT_HEADER 				=> false,
			CURLOPT_VERBOSE				=> 1
		);

		$this->exec();
	}

	protected function get(){
    $this->options = array(	
			CURLOPT_URL => $this->url,
			CURLOPT_HTTPHEADER => array('Content-Type: text/xml; charset=utf-8'),
			CURLOPT_USERPWD => $this->username . ":" . $this->password
		);

		$this->exec();
	}

	protected function to_xml($params){
		$xml = "";
		while ($item = current($params)) {
				$xml = $xml . "<" . key($params) . ">" . $item . "</" . key($params) . ">";
		    next($params);
		}

		return $xml;
	}

	protected function exec(){
		$process = curl_init();
		curl_setopt_array($process, $this->options);                                                     
		return curl_exec($process);
	}
}
?>