<?php 

/**
   * urltopng Class -- Convert URls into pngs using the urltopng api
   * @version 0.1.0
   * @author Robin Andrew <robin@cablestudios.co.uk>
   * @link https://github.com/Roboco/urltopng
   * @license http://www.opensource.org/licenses/mit-license.php MIT License
   * @package urltopng Class
   */


class urltopng {
	
	public  $final_location;

	private $_api_key = "ENTER API KEY HERE";
	private $_secret_key = "ENTER SECRET KEY HERE";	

	private $_url = 'http://url2png.com'; //Default URL
	private $_force = 'false';  # [false,always,timestamp] Default: false
	private $_fullpage = 'false';  # [true,false] Default: false
	
	private $_width = 'false'; # scaled image width in pixels; Default no-scaling.
	private $_height = 'false'; # scaled image width in pixels; Default no-scaling.
	private $_viewport = '1280x1024'; # Max 5000x5000; Default 1280x1024
	
	private $_filesave_full = 'assets/captured_webpages/fullsize/'; //for example
	private $_filesave_large = 'assets/captured_webpages/large/';
	private $_filesave_medium = 'assets/captured_webpages/medium/';
	private $_filesave_small = 'assets/captured_webpages/small/';
	private $_filesave_mobile = 'assets/captured_webpages/mobile/';
	
	private $_width_large = '1280';
	private $_width_medium = '500';
	private $_width_small = '275';
	private $_width_mobile = '275';
	
	private $_viewport_full = '1280x1024';
	private $_viewport_mobile = '320x480';
	
	
	private $_filesave_loc;
	private $_args;
	private $_pub_key;


	/**
	   * @function 			__Construct 
	   * @description 		builds config string
	   * @param string 		$url 	site address
	   * @param interger 	$pub_key 	unique id assigned to saved image
	   * @param string 		$force 	
	   * @param boolean 	$fullpage 	
	   * @param string 		$viewport 
	   * @param integer 	$width 	
	   * @param integer 	$height 	

	   * @exceptions  		$url and $pub_key required

	   * @return 			returns _urltopng().
	*/


public function __construct($url = null, $pub_key = null, $filesize = 'full', $force = null, $fullpage = null, $viewport = null, $width = null, $height = null){
	

	if(isset($url)){
	$this->_url = $url;
	} else {
		throw new Exception('url is required when creating screenshots');
	}
	if(isset($pub_key)){
	$this->_pub_key = $pub_key;
	} else {
		throw new Exception('pub key is required when creating screenshots');
	}
	if(isset($force)){
	$this->_force = $force;
	}
	if(isset($fullpage)){
	$this->_fullpage = $fullpage;
	}
	if(isset($width)){
	$this->_width = $width;
	}
	if(isset($height)){
	$this->_width = $width;
	}
	if(isset($force)){
	$this->_viewport = $viewport;
	}
	
	switch ($filesize):
	case 'full':
		$this->_filesave_loc = $this->_filesave_full;
		$this->_fullpage = 'true';
	    break;
	 case 'large':
	 	$this->_filesave_loc = $this->_filesave_medium;
	 	$this->_width = $_width_large;
		$this->_viewport = $this->_viewport_full;	 	
	 break;
	 case 'medium':
	 	$this->_filesave_loc = $this->_filesave_medium;
	 	$this->_width = $this->_width_medium;
		$this->_viewport = $this->_viewport_full;	 	
	 break;
	 case 'small':
	 	$this->_filesave_loc = $this->_filesave_small;
	 	$this->_width = $this->_width_small;
		$this->_viewport = $this->_viewport_full;
	 break;
	case 'mobile':
	 	$this->_filesave_loc = $this->_filesave_mobile;
	 	$this->_width = $this->_width_mobile;
		$this->_viewport = $this->_viewport_mobile;
	 break;
	default:
		$this->_filesave_loc = $this->_filesave_full;
	 	$this->_width = $this->_width_large;
		$this->_viewport = $this->_viewport_full;
	endswitch;
	
	
	$options['fullpage']  = $this->_fullpage;
	$options['viewport']  = $this->_viewport;
	$options['thumbnail_max_width'] = $this->_width;      
	$options['force']     = $this->_force;     

	$this->_args = $options;
	$this->final_location = $this->_urltopng();
		
}


	/**
	   * @function 			_urltopng
	   * @description 		checks if file exists agains unique id, if not creates a new one and pulls it down to the server
	   * @exceptions  		$img file does not exists on url2png server
	   * @return 			The image location.
	*/



private function _urltopng(){

	//check to see that file exists firsts
	if(file_exists($this->_filesave_loc . $this->_pub_key . '.png')){
			return $file_save_location;		
	} else {
	//this will construct the api url
	$urltopng_return = $this->_url2png_v6();
	//pull down screenshot
	$img = @file_get_contents($urltopng_return);
	
	if($img){
	//create file name, you might not want to hash id
/* 		$file_save_location = $this->_filesave_loc . $this->_pub_key . '.png'; */

	$file_save_location = $this->_filesave_loc . md5($this->_pub_key) . '.png';
	
	//save file to folder location
	file_put_contents(SITE_ROOT . $file_save_location, $img);
	return $file_save_location;
	} else {
		throw new Exception('Error getting file from url 2 png');
		return false;
	}
	}
}

	/**
	   * @function 			_url2png_v6
	   * @description 		builds api query based on settings
	   * @return 			the url to user service.
	*/


private function _url2png_v6() {
 
  $url = $this->_url;
  $args = $this->_args;
  # Get your apikey from http://url2png.com/plans
  $URL2PNG_APIKEY = $this->_api_key;
  $URL2PNG_SECRET = $this->_secret_key;
 
  # urlencode request target
  $options['url'] = urlencode($url);
 
  $options += $args;
 
  # create the query string based on the options
  foreach($options as $key => $value) { $_parts[] = "$key=$value"; }
 
  # create a token from the ENTIRE query string
  $query_string = implode("&", $_parts);
  $TOKEN = md5($query_string . $URL2PNG_SECRET);
  
  return "http://beta.url2png.com/v6/$URL2PNG_APIKEY/$TOKEN/png/?$query_string";
 
}
}
?>