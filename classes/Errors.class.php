<?php

/**
 * Handle multiple ways to show errors (text/image).
 */
class Errors {
	
	public static function catchError($level, $message, $filename='',
		$line='', $context='') {
		self::showImageMessage($message,$filename,$line);
	}
	
	public static function catchException($e) {
		self::showImageMessage($e->getMessage(),$e->getFile(),$e->getLine());
	}
	
	/**
	 * Basic text version
	 */
	public static function showTextMessage($message,$filename="",$line="") {
		//ob_clean();
		echo self::mergeMessage($message,$filename,$line);
		exit();
	}
	
	/**
	 * Show the message as an image. (JPG)
	 * In the case that the header content-type is sent.
	 */
	public static function showImageMessage($message,$filename="",$line="") {
		$width = 160;
		$maxCharPerLine = 22;
		
		//Output filters
		if($message == "")
			$message = "Unknown error.";
		
		$message = self::mergeMessage($message,$filename,$line);
		
		if(strlen($message)>250)
			$message = substr($message,0,250-5)."[...]";
		$lines = self::divideStringToArrayLines($message,$maxCharPerLine);
		
		//Image creation
		$heightLine = 12;
		$heightTotal = count($lines)*$heightLine+25;
		$img = imagecreatetruecolor($width,$heightTotal);
		
		//Some colors needed
		$colWhite = imagecolorallocate($img, 255, 255, 255);
		$colBlack = imagecolorallocate($img, 70, 69, 72);
		$colGray = imagecolorallocate($img, 125, 125, 125);
		$colRed = imagecolorallocate($img, 245, 0, 0);
		
		//Background color
		imagefilledrectangle($img, 0, 0, $width, $heightTotal, $colWhite);
		
		//Add the message
		$indexLine=0;
		foreach($lines as $l) {
			imagestring($img,3,5,$heightLine*($indexLine++),$l,$colRed);
		}
		//imagestring($img,3,5,$heightLine*($indexLine++),$filename.":".$line,$colGray);
		imagestring($img,3,5,$heightLine*($indexLine++),"Retry (Using Ctrl-F5)",$colBlack);
		
		//Output
		ob_clean();
		imagejpeg($img,'',65);
		exit();
	}
	
	private static function mergeMessage($message,$filename,$line) {
		return $message.
			($filename != "" && $line != ""?
			" (".basename($filename).":".$line.")":"");
	}
	
	/**
	 * Separate words into lines.
	 * @param String Text
	 * @param int Maximum char that can be on one line. (include white space)
	 * @return array Array of all words.
	 */
	private static function divideStringToArrayLines($string,$maxCharPerLine) {
		//Dividing line
		$words = explode(' ',$string);
		$finalLines = array();
		$currentLine = "";
		foreach($words as $w) {
			
			if($currentLine=="") //First word
				$currentLine = $w;
				
			else
			{
				$countWord = strlen($w);
				$countLine = strlen($currentLine) + $countWord + 1; //1 for space
				
				if($countLine > $maxCharPerLine){ //Last word of a line
					array_push($finalLines,$currentLine);
					$currentLine = "";
				}
				else {
					$currentLine .= " ";
				}
				
				$currentLine .= $w;
			}
		}
		array_push($finalLines,$currentLine);
		return $finalLines;
	}
}

?>