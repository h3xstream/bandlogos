<?php
include_once("DBConnection.inter.php");
include_once("DBConfig.class.php");

/**
 * MySQL implementation
 */
class MySQLNative implements DBConnection
{
	private $dbh = null;
	private static $instance = null; //Instance of the class
	
	public static function getInstance() {
		if(self::$instance == null){
			self::$instance = new MySQLNative();
			self::$instance->connection();
		}
		return self::$instance;
	}
	
	public function getDBName() {
		return "mysql";	
	}
	
	public function connection() {
		
		$this->dbh = mysql_connect(DBConfig::SERVER,DBConfig::USER,DBConfig::PASSWORD);
		mysql_select_db(DBConfig::BASE,$this->dbh);
		
	}
	
	public function disconnect() {
		mysql_close($this->dbh);
	}
	
	public function execQuery($sql,array $parameters=array()) {
		
		$sth = null;
		
		$sqlparts = explode("?",$sql);
			
		$nbNeeded = count($sqlparts)-1;
		$nbValues = count($parameters);
		if($nbNeeded == $nbValues)
		{
			$sqlfinal = "";
			for($x=0;$x<count($parameters);$x++)
			{
				$isInt = is_int($parameters[$x]);
				
				$sqlfinal .= $sqlparts[$x] . (!$isInt?"'":"") . 
					mysql_real_escape_string($parameters[$x]) . (!$isInt?"'":"");
			}
			$sqlfinal = $sqlfinal . $sqlparts[count($sqlparts)-1];
			
			//echo $sqlfinal."<br/>";
			
			$sth = mysql_query($sqlfinal,$this->dbh);
			if(!$sth)
			{
				trigger_error("Invalid query: " . mysql_error(), E_USER_ERROR);
				return;
			} 
		}
		else
		{
			trigger_error("The number of parameters is invalid (".$nbValues.") "
				.$nbNeeded." needed",E_USER_ERROR);
			
			return;
			
		}
		
		return $sth;
		
	}
	
	public function execQuerySelect($sql,array $parameters=array()) {
		$sth = $this->execQuery($sql,$parameters);
		
		$res = array();
		
		$x = 0;
		while($line = mysql_fetch_assoc($sth))
		{
			$res[$x] = $line;
			$x++;
		}
		
		return $res;
	}
	
	public function execQueryIterator($sql,array $parameters=array()) {
		$sth = $this->execQuery($sql,$parameters);
		
		return new MySQLNativeIterator($sth);
	}
	
	public function getLastInsertId() {
		$sql = "SELECT last_insert_id() as id;";
		$it = $this->execRequeteSelection($sql);
		
		
		if($ligne = $it->hasNext())
		{
			return $ligne['id'];
		}
		else
		{
			return -1;
		}
	}
}

/**
 * Iterator version MySQLNative
 */
class MySQLNativeIterator implements DBIterator {
	private $sth;
	public function __construct($sth) {
		$this->sth = $sth;
	}
	
	public function getNext() {
		return mysql_fetch_assoc($this->sth);
	}
}

?>