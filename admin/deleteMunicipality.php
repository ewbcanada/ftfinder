<?php
if($_POST['id'] && is_numeric($_POST['id'])) {

	$redirect_URL = $base . "/view/list";
	
	$rowID = $_POST['id'];
	mysqlQuery("DELETE FROM municipalities WHERE id=%s", $rowID); 
	
	$message = "Municipality Removed.";
	
} else {
	$message = "error";
}

$_SESSION['msg'] = $message; // Could use an array if more than one message was needed simultaneously.

header('Location: '.$redirect_URL);
exit;
?>