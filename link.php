<html>
<head>
	<title>Band Logos - LastFM banner</title>
	
	<link rel="stylesheet" type="text/css" href="css/styles.css"/>
</head>
<body>

<?php
include_once("classes/Config.class.php");

$user=(isset($_GET['user'])? htmlentities($_GET['user']):'');
$nb=(isset($_GET['nb'])? htmlentities($_GET['nb']):10);
$type=(isset($_GET['type'])? htmlentities($_GET['type']):'overall');
$color=(isset($_GET['color'])? htmlentities($_GET['color']):'white');
$layout=(isset($_GET['layout'])? htmlentities($_GET['layout']):'OneCol');

$image = Config::LINK_BANNER
	."?user=".$user."&nb=".$nb."&type=".$type.
	"&color=".$color."&layout=".$layout;
?>


<a href="index.php">Back to index</a><br/>
<br/>
<b>BBCode Link:</b><br/>
<input type="text" size="100" value="[img]<?php echo $image ?>[/img]"
	onclick="this.focus();this.select()" style="font-size:17px"><br/>
<br/>
<b>Preview:</b><br/>
<img src="<?php echo $image ?>"><br/>



</body>
</html>