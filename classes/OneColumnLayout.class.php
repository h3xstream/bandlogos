<?php
include_once("Layout.inter.php");
include_once("Artists.class.php");
include_once("util/ImageUtil.class.php");
include_once("Config.class.php");

/**
 * Generate a image with logos in one column
 */
class OneColumnLayout implements Layout {	
	//Various settings
	private static $marginSide = 4; //Side margin (in px)
	private static $marginTop = 4; //Between the first logo and top
	private static $width = 168;
	private static $seperation = 5; //Separation between logos
	private static $baseDirectoryLogos = 'logos/';
	
	private $artists = array();
	private $nbArtists = 0;
	private $color = '';
	
	public function setArtists(array $artists) {
		$this->artists = $artists;
	}
	
	public function setNbArtists($nbArtists) {
		$this->nbArtists = $nbArtists;
	}
	
	public function setColor($color) {
		$this->color = $color;
	}
	
	public function getName() {
		return "OneCol";
	}
	
	/**
	 * Output the image with the logos of each artist
	 */
	public function show() {
		if(count($this->artists) == 0)
			throw new Exception("Missing some informations (artists).");
		
		$totalHeight=0;
		$listImageRessources = array();
		
		foreach($this->artists as $name) {
			if(count($listImageRessources) == $this->nbArtists)
				break;
			
			$a = Artists::getArtistByName($name);
			
			if($a != null) //Artist is found
			{
				$image = Artists::imageFromArtist($a);
				
				if(isset($image)) {
					$totalHeight += imagesy($image)+self::$seperation;
					array_push($listImageRessources,$image);
				}
				
			}
		}
		//echo $totalHeight;
		$totalHeight += self::$marginTop;
		
		$container = imagecreatetruecolor(self::$width,$totalHeight);
		
		//Some colors needed
		$colWhite = imagecolorallocate($container, 255, 255, 255);
		
		//Background color
		imagefilledrectangle($container, 0, 0, self::$width, $totalHeight, $colWhite);
		
		
		$expectWidth = self::$width - (2 * self::$marginSide);
		
		$currentHeight = self::$marginTop;
		foreach($listImageRessources as $image) {
			$widthImg = imagesx($image);
			//echo $this->width."-2 * ".self::$marginSide);
			//echo $widthImg."--".$expectWidth."<br/>";
			
			imagecopymerge($container,
				$image,
				self::$marginSide +
					($widthImg < $expectWidth ?($expectWidth - $widthImg)*.5 :0),
				$currentHeight,
				0,
				0,
				imagesx($image),
				imagesy($image),
				100);
				
			$currentHeight += imagesy($image) + self::$seperation;
		}
		
		ImageUtil::applyFilter($container,$this->color);
		
		imagepng($container);
	}
	
	
}

?>