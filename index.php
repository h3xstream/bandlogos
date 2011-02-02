<html>
<head>
	<title>Band Logos - LastFM banner</title>

	<link rel="stylesheet" type="text/css" href="css/styles.css"/>
</head>
<body>


<h2>Band logos - LastFM banner</h2>


<form name="bannerlink" action="link.php" method="get">
	<fieldset class="generate_form">
	<table>
	<tr>
	<td><nobr>Username :</nobr></td> <td><input type="text" name="user" value=""/></td>
	</tr><tr>
	<td><nobr>Number of logos :</nobr></td>
	<td>
		<select name="nb">
			<option value="5">5</option>
			<option value="10" selected>10</option>
			<option value="15">15</option>
			<option value="20">20</option>
			<option value="25">25</option>
		</select>
		</td>
	</tr><tr>
	<td><nobr>Type :</nobr></td>
	<td>
		<select name="type">
			<option value="overall">Overall</option>
			<option value="12month">Last year</option>
			<option value="6month">Last 6 months</option>
			<option value="3month">Last 3 months</option>
		</select>
		
	</td>
	</tr><tr>
	<td><nobr>Background color :</nobr></td>
	<td>
		<select name="color">
			<option value="white">Black on White</option>
			<option value="black">White on Black</option>
			<option value="gray">Black on Gray</option>
			<option value="trans">Black on Transparent</option>
			<option value="blue">Blue on Transparent</option>
			<option value="red">Red on Transparent</option>
			<option value="orange">Orange on Transparent</option>
			<option value="turquoise">Turquoise on Transparent</option>
		</select>
	</td>
	</tr><tr>
	<td><nobr>Layout :</nobr></td>
	<td>
		<select name="layout">
			<option value="OneCol">One Column</option>
			<option value="TwoCols">Two Columns</option>
		</select>
	</td>
	</tr>
	</table>
	<br/>
	<input type="submit" class="btn" value="Generate"/>
	</fieldset>
</form>

</body>
</html>