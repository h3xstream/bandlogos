<?php

class ImageUtil {
	
	const GRAY_VALUE = 239;
	
	/**
	 * Invert color from an image ressource.
	 * 
	 * @param ressource $image Image
	 * "param string $color ID of a filter
	 */
	public static function applyFilter($image,$color) {
		if($color == 'black')
			ImageUtil::addInvertFilter($image);
		
		else if($color == 'gray')
			ImageUtil::addGrayFilter($image);
		
		else if($color == 'blue')
			self::addColorFilter($image,array(40,79,148));
		
		else if($color == 'red')
			self::addColorFilter($image,array(214,17,2));
			
		else if($color == 'orange')
			self::addColorFilter($image,array(222,54,0));
		
		else  if($color == 'turquoise')
			self::addColorFilter($image,array(69,168,196));
		
		else  if($color == 'trans')
			self::addTransFilter($image);
		
		else if($color == 'white')
			return;
		
		else
			throw new Exception("Unknown filter.");
	}
	
	
	/**
	 * Invert color from an image ressource.
	 * 
	 * @param ressource $image Image
	 */
    public static function addInvertFilter($image) {
		
		$width = imagesx($image);
		$height = imagesy($image);
	 
		for($x=0; $x<$width; $x++)
		{
			for($y=0; $y<$height; $y++)
			{
				$oldCol = self::getColorArray($image,$x,$y);
				$newColor = self::getInvertColor($oldCol);
				imagesetpixel($image, $x, $y, self::getColRef($image,$newColor));
			}
		}
    }
    
    /**
	 * Make the image darker and make the background transparent.
	 * 
	 * @param ressource $image Image
	 */
    public static function addTransFilter($image) {
	    
	    $width = imagesx($image);
		$height = imagesy($image);
		
		//Set gray as the transparent color
		$ref = imagecolorresolve($image,self::GRAY_VALUE,self::GRAY_VALUE,self::GRAY_VALUE);
	    imagecolortransparent($image,$ref);
		
		for($x=0; $x<$width; $x++)
		{
			for($y=0; $y<$height; $y++)
			{
				$oldCol = self::getColorArray($image,$x,$y);
				$newColor = self::getDarkerColor($oldCol,self::GRAY_VALUE);
				imagesetpixel($image, $x, $y,self::getColRef($image,$newColor,$ref));
			}
		}
	}
    
    /**
	 * Make the image darker.
	 * - White pixel become darker.
	 * - Black pixel stay the same.
	 * 
	 * @param ressource $image Image
	 */
    public static function addGrayFilter($image) {
    	$width = imagesx($image);
		$height = imagesy($image);
		
	 
		for($x=0; $x<$width; $x++)
		{
			for($y=0; $y<$height; $y++)
			{
				$oldCol = self::getColorArray($image,$x,$y);
				$newColor = self::getDarkerColor($oldCol,self::GRAY_VALUE);
				imagesetpixel($image, $x, $y,self::getColRef($image,$newColor));
			}
		}
    }
	
    /**
     * Replace the black color and dark tone to the given color.
     *
	 * @param ressource $image Image
	 * @param array $color Color that will replace black
	 */
	private static function addColorFilter($image,array $color) {
	    $width = imagesx($image);
		$height = imagesy($image);
		
		$ref = imagecolorresolve($image,self::GRAY_VALUE,self::GRAY_VALUE,self::GRAY_VALUE);
	    imagecolortransparent($image,$ref);
		
		for($x=0; $x<$width; $x++)
		{
			for($y=0; $y<$height; $y++)
			{
				$oldCol = self::getColorArray($image,$x,$y);
				$newColor = self::changeDarkToSpecColor(
					self::getDarkerColor($oldCol,self::GRAY_VALUE),$color);
				imagesetpixel($image, $x, $y, self::getColRef($image,$newColor,$ref));
			}
		}
    }
    
    ////// Color basic util. //////
    
    /**
     * Simple shortcut to get a color ref for the current image.
     * 
     * @param ressource $image Image
     * @param array $newColor RGB color array of the color added
     * @param int $transRef If set should  reference the transparent color id
     * @return Return the ref id to the new color (can only be use for the given image)
     */
    private static function getColRef($image,array $newColor,$transRef=NULL) {
	    $ref = imagecolorresolve($image,$newColor[0],$newColor[1],$newColor[2]);
	    if($transRef && (array_sum($newColor) >= (self::GRAY_VALUE-15)*3))
	    	$ref = $transRef;//imagecolortransparent($image,$ref);
	    return $ref;
	}
    
	/**
	 * @param ressource $image Image
	 * @param int $x X position
	 * @param int $y Y position
	 * @return Get the RGB array for a given position
	 */
	private static function getColorArray($image,$x,$y) {
	    $pos = imagecolorat($image, $x, $y);
		$f = imagecolorsforindex($image, $pos);
		$col = NULL;
		
		if(count($f) == 3) {
			$col = array($f['red'],$f['green'],	$f['blue']);
		}
		else {
			$gst = $f['red']*0.15 + $f['green']*0.5 + $f['blue']*0.35;
			$col = array($gst,$gst,$gst);
		}
		
		return $col;
	}
	
    ////// Color modifications pattern //////
    
    /**
     * @param array $color RGB color
     * @return the RGB opposite
     */
    private static function getInvertColor(array $color) {
	    return array(255-$color[0],255-$color[1],255-$color[2]);
    }
    
    /**
     * @param array $color RGB color
     * @param int $gray Gray Level (0-255) Lower is darker
     * @return a RGB color darker than the original
     */
    private static function getDarkerColor(array $color,$gray) {
    	$color[0] -= ($color[0]/255*(255-$gray));
    	
    	//Base on the assumption the color are grayscale
    	$color[2] = $color[1] = $color[0];
    	
    	/*
    	$color[1] -= ($color[1]/255*(255-$gray));
    	$color[2] -= ($color[2]/255*(255-$gray));*/
    	
    	return $color;
    }
    
    /**
     * 
     */
    private static function changeDarkToSpecColor(array $color,array $newColor) {
	    $sum = array_sum($color);
	    
	    if($sum < (self::GRAY_VALUE-15)*3) {
	    	$color[0]+=$newColor[0]*(255-$color[0])/255;
	    	$color[1]+=$newColor[1]*(255-$color[1])/255;
	    	$color[2]+=$newColor[2]*(255-$color[2])/255;
    	}
		return $color;
	}
}
?>