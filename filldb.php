<?php
error_reporting(E_ALL);
$_PATH['classes'] = 'classes/';
include_once($_PATH['classes'].'Artists.class.php');
include_once($_PATH['classes'].'Config.class.php');

$nbInsert = 0;

function artistExist($name) {
	
	$db = Config::$dbInstance;
	
	//Manual query to avoid loading all the column
	$sql = "SELECT name ".
			"FROM lastfm_artists where name = ?";
	
	$it = $db->execQueryIterator($sql,array($name));
	if($line = $it->getNext()) {
		return true;
	}
	else {
		return false;
	}
}

function loadLogos($dir)
{
	if(!is_dir($dir))
	{
		echo "Invalid directory";
	}
	else
	{
		global $nbInsert;
		global $_PATH;
		
		if($handle = opendir($dir))
		{
			while(($file = readdir($handle)) !== false)
			{
				///---Extraction
				$parts = explode('.',$file);
				$ext = array_pop($parts);
				
				///---Valid formats
				if(!($ext == 'gif' || $ext == 'jpg' || $ext == 'png'))
					continue;
				
				///---Name repack
				$name = join(".",$parts);
				$name = str_replace('__','.',$name);
				$name = str_replace('_','%',$name);
								
				///---Extra Validations
				
				//Check for special caracters not encoded
				if(strpos($name,'_') !== false || strpos($name,' ') !== false)
				{
					echo "<b>".$name." sould be rename to ".
						str_replace(array('_',' '),array('+','+'),$name).".</b><br/>";
					continue;
				}
				
				else if(strpos($name,'%') === false){
					$nameCheck = urlencode(str_replace(array('+'),array(''),$name)); //Char excludes
					
					//Some caracter are not support yet
					if(strpos($nameCheck,'%') !== false){
						echo "<b>".$name." is not properly parse.</b><br/>";
						continue;
					}
				}
				
				$prev = $name;
				
				///---Insert in db
				
				if(artistExist($name)) {
					continue;
				}
				
				echo $name." - ";
				
				$newDate=filemtime(Config::FOLDER_LOGOS.$file); //Date of modification (new file)
				$newArtist = new Artist($name,$file,"",-1,$newDate);
				Artists::addArtist($newArtist);
				$nbInsert++;
				
			}
			closedir($handle);
		}
	}
}

//--Ini
set_time_limit(360);
$start = microtime(true);


//--Empty list
if(isset($_GET['clear']))
	Artists::emptyList();

//--Fill db base on the file this folder
loadLogos(Config::FOLDER_LOGOS);

$end = microtime(true);
echo $nbInsert." artists inserted in ".round(($end - $start),3)." sec.";

?>