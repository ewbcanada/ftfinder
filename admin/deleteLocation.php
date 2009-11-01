<?php
if($_POST['id'] && is_numeric($_POST['id'])) {

	$redirect_URL = $base . "/view/list";
	
	$rowID = $_POST['id'];
	$gid = mysqlFetchArray(mysqlQuery("SELECT geoCodeId FROM locations WHERE id=%s", $rowID));
	mysqlQuery("DELETE FROM locations WHERE id=%s", $rowID);
	mysqlQuery("DELETE FROM geocodes WHERE id=%s", $gid);
	
	$message = "Location Removed.";
	
} else {
	$message = "error";
}

$_SESSION['msg'] = $message; // Could use an array if more than one message was needed simultaneously.

header('Location: '.$redirect_URL);
exit;

?>
