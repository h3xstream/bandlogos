<html>
<head>
	<title>Logos</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>

<?php

error_reporting(E_ALL);

$_PATH['classes'] = 'classes/';
include_once($_PATH['classes'].'Config.class.php');
include_once($_PATH['classes'].'Artists.class.php');
include_once($_PATH['classes'].'util/UnicodeEntities.class.php');

$_PATH['logos'] = Config::FOLDER_LOGOS;

$artists = Artists::getListArtists();

$uniReplace = new UnicodeEntities();

echo "
<h2>Logos</h2>Currently ".count($artists)." different logos are included.<br/>

<hr color=\"#000\"/>";

//List of letters
echo "<center>";
$letter=35;
echo "<a href=\"#".chr($letter)."\">".chr($letter)."</a> ";
for($letter = 65;$letter<91;$letter++) {
	echo "<a href=\"#".chr($letter)."\">".chr($letter)."</a> ";
}
echo "</center>\n\n";

$uniReplace = new UnicodeEntities();

$prevAscii = -1;

//List of artists
foreach($artists as $a) {
	$name = $a->getName();
	$file = $a->getPathLogo();
	$ascii = ord($name);

	
	if($ascii != $prevAscii && 64 < $ascii && $ascii < 91) {
		echo "<h3><a name=\"".chr($ascii)."\">".chr($ascii)."</a></h3>\n";
		$prevAscii = $ascii;
	}
	else if($prevAscii == -1) {
		echo "<h3><a name=\"#\">#</a></h3>\n";
		$prevAscii = 0;
	}


	$nameFilter = urldecode($name);
	$nameFilter = str_replace(array('%23','%2F','"','%2B','%26','<','>','%5C'),array('#','/','&quot;','+','&','&lt;','&gt;','\\'),$nameFilter);
	//$nameFilter = $uniReplace->UTF8entities($nameFilter);
        
	echo "<a href=\"http://last.fm/music/".$name."\">".$nameFilter."</a><br/>\n";
}
echo "<hr color=\"#000\"/>";

?>

</body>
</html>