<?php
// Centralized config

//offline mode
define('OFFLINE', false);

$db_server = 'localhost';
$db_user = 'root';
$db_pass = '';

$db_name = 'ftmap';

$base = "/ftmap";

//localhost
//define('KEY', 'ABQIAAAAWBt7bhynZ2yf6gDKJ2ceYxT2yXp_ZAY8_ufC3CFXhHIE1NvwkxSyhqr6Fao0cQhgPae2452DdT_q0w');

//172.23.1.117
define('KEY', 'ABQIAAAAWBt7bhynZ2yf6gDKJ2ceYxSjOee52JUlIRuHcghttA2hwj3VfhQvLiBswdTgecJRPR_RvjWllWjXXg');


mysql_connect($db_server, $db_user, $db_pass) or die(mysql_error());
mysql_select_db($db_name) or die(mysql_error());


?>
