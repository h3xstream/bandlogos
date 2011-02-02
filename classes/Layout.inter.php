<?php

interface Layout {
	
	public function getName(); 
	
	/**
	 * Define artists to show.
	 * @param array $artists List of artists (String)
	 */
	public function setArtists(array $artists);
	
	/**
	 * Define the number of artists to show.
	 * @param int $nbArtists Number of artists
	 */
	public function setNbArtists($nbArtists);
	
	
	/**
	 * Define the color of the background
	 * @param string $color Color (white, black)
	 */
	public function setColor($color);
	
	
	/**
	 * Image showing the logos associate to the artists
	 */
	public function show();
	
}

?>