<?php
require_once("_config.php");

$name = $_POST['name']; //sanitize
$zoom = $_POST['zoom']; //TODO:sanitize
$id = $_POST['id']; //sanitize

$redirect_URL = "$base/view/";

if($name && $zoom && $id && !OFFLINE)
{
	$name2 = urlencode($name);
	$geoCode = geoCodeConvert($name2);
	$geoCode = $geoCode['latitude'].",".$geoCode['longitude'];
	
	if($geoCode)
	{
		$message = 'Modified';
		if($id=="new")
		{
			mysqlQuery("INSERT INTO municipalities(name, zoom, longitude, latitude) VALUES ('0', '0', '0', '0')");
			$id = mysql_insert_id();
			$message = 'Added';
		}
		//FIXME: update to new database structures. 
		$query = "UPDATE municipalities SET name='%s', geoCode='%s', zoom = '%s' WHERE id='%s'";
		
		mysqlQuery($query, array($name, $geoCode, $zoom, $id));
		
		$message .= ' municipality successfully. gCode = '.$geoCode . " " . $name . " " . $zoom . " ". $id;
		
		$redirect_URL .= 'list';
	}
	else 
	{
		$message = 'Error: Not a valid Municipality.';
		$redirect_URL .= 'editMunicipality';
	}
}
else 
{
	if(OFFLINE) {
		$message = "operating in offline mode";
		$redirect_URL .= "list";
	} else {
		$message = 'Error: All fields must be filled in.';
		$redirect_URL .= 'editMunicipality';
	}
}



$_SESSION['msg'] = $message; // Could use an array if more than one message was needed simultaneously.

header('Location: '.$redirect_URL);
exit;
?>