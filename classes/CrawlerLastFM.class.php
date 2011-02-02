<?php
include_once("Config.class.php");

/**
 * Read the audioscrobber feeds.
 * Request can be made using 3 php methods (see Config.class.php)
 */
class CrawlerLastFM {
	
	
	public function __construct()
	{
		
	}
	
	/**
	 * Optain the list of Top artists from a user
	 * @param string $accountName Unique name
	 * @param int $nbArtists Number of artists to list
	 */	
	public function getListTopArtists($username,$type='overall',$nbArtists=0) {
		
		if(strlen($username) == 0 || strlen($username) > 15)
			throw new Exception("Invalid username.");
		
		$lines = $this->request(
			"http://ws.audioscrobbler.com/2.0/?method=user.gettopartists".
			"&user=".$username.
			"&period=".$type.
			"&api_key=".Config::API_KEY);
			/*'http://ws.audioscrobbler.com/1.0/user/'.$username.
			'/topartists.xml?type='.$type);*/
		
		if(count($lines) == 0)
			throw new Exception("Feed is empty.");
		
		$artists = array();
		$noartist=0;
		foreach($lines as $line) {
			
			//Skip useless line
			if(strrpos($line,"<url>http://www.last.fm/music/") === false)
				continue;
			
			//Extract the encode name from the url
			preg_match("(http://www.last.fm/music/([^<]+))",$line,$proper);
			
			array_push($artists,$proper[1]);
			
			$noartist++;
			
			if($noartist == $nbArtists) //Stop when max 
				break;
		}
		
		if(count($artists) == 0)
			throw new Exception("No Artist found. Invalid feed??");
		
		return $artists;
	}
	
	
	
	/**
	 * Get the content of the URL specified
	 * @param string $url Path containing http://
	 */
	private function request($url) {
		switch(Config::REQUEST_METHOD) {
			case "cUrl":
				return $this->requestCurl($url);
			case "file_get_contents":
				return $this->requestFileGet($url);
			default:
			case "fopen":
				return $this->requestFOpen($url);
		}
	}
	
	private function requestCurl($url) {
		$lines = array();
		
		if( !extension_loaded('curl') ){
                throw new Exception("Curl is not install or activate.");
        }
        
        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $url);
        curl_setopt($curlHandle, CURLOPT_HEADER, 0);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, 10);

        $content = curl_exec($curlHandle);
        //trigger_error($content);
        curl_close($curlHandle);
		
		//$content = LiteHttp::getUrlContent($url);
		$lines = explode("\n",$content);
		return $lines;
	}
	
	private function requestFopen($url) {
		$lines = array();
		$remoteRead = fopen($url, "r");
		
		if($remoteRead) {
			while (!feof($remoteRead)) {
				array_push($lines,fgets($remoteRead, 4096));
			}
			fclose($remoteRead);
		}
		else {
			throw new Exception("Error accessing the feed.");
		}
		
		return $lines;
	}
	
	
	private function requestFileGet($url) {
		$lines = array();
		
		$content = file_get_contents($url);
		$lines = explode("\n",$content);
		
		return $lines;
	}
}

?>