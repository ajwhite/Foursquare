<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Foursquare extends CI_Controller {

	
	private $client_key = 'PEC5EVXETZUG2Z5NYF3Y1OGZQWZB0R5NP2U4THLBNIGJLSKX';//'3MKPEV0UUCZINGTP1OAVLOYNPRT0CYC0WLNJZOWJ1XXQZJ11';
	private $client_secret = 'RLAJ54HMT0GB1IPTBNXPIOF24TSPH0YXHIXHH5RD1YRDWEOO';//'SKEHE1CMZC3X0WAYYERYH4BU5OB5XMJDXTMSJYEDUIMBA0V5';
	private $redirect_uri = 'http://foursquare.atticuswhite.com/index.php/foursquare';
	private $token = 'TP1A5PTSCEUDH21ZQYRNBSCOWG43C3NQQLFEQKYHIL43KHC4';//'OGULX0W1PKZJM0IYP3JOAGIL3BOLUPUGB1AGIBUTPCGZ0K4W';
	private $fsqr = null;

	

	
	public function index()
	{
		$this->load->library('FoursquareApi');
		$this->fsqr = new FoursquareApi($this->client_key, $this->client_secret);
		
		// If the link has been clicked, and we have a supplied code, use it to request a token
		if(array_key_exists("code",$_GET)){
			$token = $this->fsqr->GetToken($_GET['code'],$this->redirect_uri);
		}

		if(!isset($token)){ 
			echo "<a href='".$this->fsqr->AuthenticationLink($this->redirect_uri)."'>Connect to this app via Foursquare</a>";
		// Otherwise display the token
		}else{
			echo "Your auth token: $token";
		}

	
		//$this->load->view('welcome_message');
	}
	
	function search(){
		$address = $this->input->post('address');
		if (strlen($address)>0){
			$this->geolocate($address);
		} else {
			$this->load->view('search');
		}
	}
	
	function geolocate($address){
		$this->load->library('FoursquareApi');
		$this->fsqr = new FoursquareApi($this->client_key, $this->client_secret);
		$this->fsqr->SetAccessToken($this->token);
		
		list($lat,$lng) = $this->fsqr->GeoLocate($address);
		$params = array('ll'=>"$lat,$lng");
		
		$response = $this->fsqr->GetPublic("venues/search", $params);
		$this->load->view('venues', array('venues'=>json_decode($response)));
	}
	
	
	function checkin($id){
	
		$this->load->library('FoursquareApi');
		$this->fsqr = new FoursquareApi($this->client_key, $this->client_secret);
		$this->fsqr->SetAccessToken($this->token);
		$this->fsqr->checkin($id);
	}
	
	function geo(){
		$this->load->library('FoursquareApi');
		$this->fsqr = new FoursquareApi($this->client_key, $this->client_secret);
		$this->fsqr->SetAccessToken($this->token);
		
		//list($lat,$lng) = $this->fsqr->GeoLocate("680 N Pleasant St, Amherst, MA");
		//$params = array("ll"=>"$lat,$lng");
		$params = array('ll'=>'42.395501,-72.532210');
		
		$response = $this->fsqr->GetPublic("venues/search",$params);
		$venues = json_decode($response);
		
		echo "<pre>";
		print_r($venues);
		echo "</pre>";
		
		$result = $this->fsqr->checkin('4b4f8cbff964a5200b0b27e3'); // computer sciences
		//$result = $this->fsqr->checkin('4b5615fef964a520abff27e3'); // library
		//print_r($result);
	}
	
}