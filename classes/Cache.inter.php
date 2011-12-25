<?php

interface Cache {
	
	//Refer to the forms parameter
	
	public function setUser($user);
	
	public function setPeriodType($type);
	
	public function setNbArtists($nbArtists);
	
	public function setColor($color);
	
	/**
	 * @param Layout $layout Instance that can generate an image.
	 */
	public function setLayout(Layout $layout);
	
	
	
	/**
	 * Generate the image.
	 * Post-condition: previous fields need to be initialized.
	 */
	public function generate();
	
	/**
	 * Ouput the result.
	 * No content (except header) should have been ouput before this method call.
	 * To allow header to be add.
	 */
	public function outputResult();
}

?>