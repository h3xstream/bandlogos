<?php
include_once("Layout.inter.php");
include_once("Artists.class.php");
include_once("util/ImageUtil.class.php");
include_once("Config.class.php");

/**
 * Generate an image with logos on two columns
 */
class TwoColumnsLayout implements Layout {
	private static $marginTop = 4; //Between the first logo and top
	private static $width = 300;
	private static $seperation = 4; //Separation between logos
	
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
		return "TwoCols";
	}
	
	/**
	 * Output the image with the logos of each artist
	 */
	public function show() {
		if(count($this->artists) == 0)
			throw new Exception("Missing some informations (artists).");
			
		$totalHeight = array(self::$marginTop,self::$marginTop);
		$listImageRessources = array(array(),array());
		
		$forcedWidth = (self::$width/2) - (self::$seperation/2);
		
		foreach($this->artists as $name) {
			if(count($listImageRessources[0])+count($listImageRessources[1]) 
				== $this->nbArtists)
				break;
			
			$a = Artists::getArtistByName($name);
			
			if($a != null) //Artist is found
			{
				$image = Artists::imageFromArtist($a);
				
				if(isset($image)) {
					$i = ($totalHeight[1]<$totalHeight[0]?1:0);
					
					$x = imagesx($image);
					$y = imagesy($image);
					$newHeight = floor(($y * $forcedWidth) / $x);
					
					$totalHeight[$i] += $newHeight+self::$seperation;
					array_push($listImageRessources[$i],$image);
				}
			}
		}
		
		
		$container = imagecreatetruecolor(self::$width,max($totalHeight));
		
		//Some colors needed
		$colWhite = imagecolorallocate($container, 255, 255, 255);
		
		//Background color
		imagefilledrectangle($container, 0, 0, self::$width, max($totalHeight), $colWhite);
		
		
		$currentHeight = array(self::$marginTop,self::$marginTop);
		for($l=0;$l<count($listImageRessources);$l++) {
			$list = $listImageRessources[$l];
			
			foreach($list as $image) {
				$x = imagesx($image);
				$y = imagesy($image);
				
				$newHeight = floor(($y * $forcedWidth) / $x);
				
				$resized = imagecreatetruecolor($forcedWidth, $newHeight);				
				imagecopyresampled(
					$resized,
					$image,
					0,0,0,0,
					$forcedWidth,
					$newHeight,
					$x,
					$y);
				
				imagecopymerge($container,
					$resized,
					($forcedWidth+self::$seperation) * $l,
					$currentHeight[$l],
					0,
					0,
					$forcedWidth,
					$newHeight,
					100);
				
				//echo $currentHeight[$l] . "-";
				$currentHeight[$l] += $newHeight + self::$seperation;
			}
		}
		
		
		ImageUtil::applyFilter($container,$this->color);
		
		imagepng($container);
	}
}

?>