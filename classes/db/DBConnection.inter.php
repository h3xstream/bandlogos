<?php

/**
 * Model of interactions with a database
 */
interface DBConnection
{
	public static function getInstance();
	
	/**
	 * @return String that describe the type of database (ex: MySQL, MSSQL, ....)
	 */
	public function getDBName();
	
	/**
	 * Connect to the database. (Initialisations also goes here.)
	 */
	public function connection();
	
	/**
	 * End the connection
	 */
	public function disconnect();
	
	/**
	 * Execute a query (INSERT, UPDATE, SELECT, ...)
	 * @return mixed Results set specific to the db. (Use for encapsulation)
	 */
	public function execQuery($sql,array $parameters=array());
	
	/**
	 * Execute a query and return a Iterator
	 * @return DBIterator Iterator and browse one line at the time.
	 */
	public function execQueryIterator($sql,array $parameters=array());
	
	/**
	 * Execute a query and return filled array.
	 * @return array List of the result. Each result is a hash.
	 * @deprecated 1.0 - 2008-09-26
	 * @link execQueryIterator() Use this method instead.
	 */
	public function execQuerySelect($sql,array $parameters=array());
	
	/**
	 * @return int Last primary key generate from an insert.
	 */
	public function getLastInsertId();
	
}

/**
 * Browse result from a query and keeping abstraction from the db.
 */
interface DBIterator {
	
	public function getNext();
}
?>