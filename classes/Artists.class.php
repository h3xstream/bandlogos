<?php
include_once("Config.class.php");

/**
 * Acces the artist data
 */
class Artists {
	
	private static $baseDirectoryLogos = Config::FOLDER_LOGOS;
	
	/**
	 * @param string Name of the artist
	 * @return Artist Infos of the artist
	 */
	public static function getArtistByName($name) {
		//$db = MySQLNative::getInstance();
		$db = Config::$dbInstance;
		
		$sql = "SELECT * ".
			"FROM lastfm_artists ".
			"WHERE name = ?";
		$values= array($name);
		$it = $db->execQueryIterator($sql,$values);
		
		if($line = $it->getNext()) {
			$artist = new Artist($line['name'],
				$line['path_logo'],
				$line['lastfm_uid'],
				$line['idartist'],
				$line['date_added']);
				
			return 	$artist;
		}
		else {
			return null;
		}
	}
	
	/**
	 * @return array List of artists
	 */
	public static function getListArtists() {
		$db = Config::$dbInstance;
		
		$sql = "SELECT * ".
			"FROM lastfm_artists ".
			"ORDER BY name ASC";
		$it = $db->execQueryIterator($sql,array());
		
		$artists = array();
		while($line = $it->getNext()) {
			$artist = new Artist($line['name'],
				$line['path_logo'],
				$line['lastfm_uid'],
				$line['idartist'],
				$line['date_added']);
				
			array_push($artists,$artist);
		}
		return $artists;
	}
	
	/**
	 * Clear existing artists in db
	 */
	public static function emptyList() {
		$db = Config::$dbInstance;
		
		//$sql = "DELETE FROM lastfm_artists";
		$sql = "TRUNCATE TABLE lastfm_artists";
		$db->execQuery($sql);
	}
	
	/**
	 * Add a artist to the list in db
	 * @param Artist $artist Basic infos of the artist
	 */
	public static function addArtist(Artist $artist) {
		$db = Config::$dbInstance;
		
		$sql = "INSERT INTO lastfm_artists(name,path_logo,lastfm_uid,date_added) ".
			"VALUES(?,?,?,?)";
		$values = array($artist->getName(),$artist->getPathLogo(),
			$artist->getLastfmId(),$artist->getDateAdded());
		$db->execQuery($sql,$values);
		
	}
	
	/**
	 * 
	 * @param Artist $artist Infos of the artist
	 * @return resource Resource of the image
	 */
	public static function imageFromArtist(Artist $artist) {
		$image = null;
		$path = self::$baseDirectoryLogos.$artist->getPathLogo();
		
		switch ($artist->getExtensionLogo()) {
			case 'gif':
				$image = imagecreatefromgif($path);
				break;
			case 'jpeg':
			case 'jpg':
				$image = imagecreatefromjpeg($path);
				break;
			case 'png':
				$image = imagecreatefrompng($path);
				break;
		}
		return $image;
	}
}

class Artist {
	private $idArtist;
	private $name;
	private $pathLogo;
	private $lastfmId;
	private $dateAdded;
	
	private $ext = null;
	
	public function __construct($name,$pathLogo,$lastfmId="",$idArtist=-1,$dateAdded=0) {
		$this->name = $name;
		$this->pathLogo = $pathLogo;
		$this->lastfmId = $lastfmId;
		$this->dateAdded = $dateAdded;
	}
	
	public function getIdArtist() {
		return $this->idArtist;;
	}
	
	public function getName() {
		return $this->name;;
	}
	
	public function getPathLogo() {
		return $this->pathLogo;
	}
	
	public function getLastfmId() {
		return $this->lastfmId;
	}
	
	public function getDateAdded() {
		return $this->dateAdded;
	}
	
	public function getExtensionLogo() {
		if($this->ext == null) {
			$parts = explode('.',$this->pathLogo);
			$ext = array_pop($parts);
		}
		
		return $ext;
	}
}
?>