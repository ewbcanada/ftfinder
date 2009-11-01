<?php
$address = $_POST['address']; 
$name = $_POST['name']; //sanitize
$mun = $_POST['municipality']; //TODO:sanitize
$id = $_POST['id']; //sanitize

$redirect_URL = "$base/view/";

require_once("_config.php");

if($address && $name && !OFFLINE)
{
	$gCode = geoCodeConvert($address);

	if($gCode)
	{		
		$message = 'Modified';
		if(!$id)
		{
			mysqlQuery("INSERT INTO geocodes(latitude, longitude) VALUES ('0', '0')");
			$gid = mysql_insert_id();
			mysqlQuery("INSERT INTO locations(address, geoCodeId, name, municipality_id) VALUES ('0', '0', '0', '0')");
			$id = mysql_insert_id();
			$message = 'Added';
		}
		$query = "UPDATE geocodes SET latitude='%s', longitude='%s' WHERE '$s'";
		mysqlQuery($query, array($gCode['latitude'], $gCode['longitude'], $gid));
		$query = "UPDATE locations SET address='%s', geocodeId='%s', name='%s', municipality_id='%s' WHERE id='%s'";
		mysqlQuery($query, array($address, $gid, $name, $mun, $id));
		
		$message .= ' GeoCode successfully. gCode = '.$gCode['latitude'] .",". $gCode['longitude'] . " " . $name . " " . $address . " ". $mun . " ". $id;
		
		$redirect_URL .= 'list';
	}
	else 
	{
		$message = 'Error: Not a valid address.';
		$redirect_URL .= 'editLocation';
	}
	
} 
else 
{
	if (OFFLINE) {
		$message = "operating in offline mode.";
		$redirect_URL .='list';
	} else {	
		$message = 'Error: Not a valid GeoCode.';
		$redirect_URL .= 'editLocation';
	}
}

$_SESSION['msg'] = $message; // Could use an array if more than one message was needed simultaneously.

header('Location: '.$redirect_URL);
exit;
?>
