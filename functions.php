<?php
/**************************************************************************
 * The Fair Trade Finder                                                  *
 *                                                                        *
 * Copyright 2008-2009  Engineers Without Borders Canada                  *
 * http://itsupport.ewb.ca                                                *
 *                                                                        *
 * The Fair Trade Finder is free software: you can redistribute it        *
 * and/or modify it under the terms of the GNU General Public License     *
 * as published by the Free Software Foundation, either version 3 of      *
 * the License, or (at your option) any later version.                    *
 *                                                                        *
 * The Fair Trade Finder is distributed in the hope that it will be       *
 * useful, but WITHOUT ANY WARRANTY; without even the implied warranty    *
 * of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the       *
 * GNU General Public License for more details.                           *
 *                                                                        *
 * You should have received a copy of the GNU General Public License      *
 * along with this software.  If not, see <http://www.gnu.org/licenses/>. *
 *                                                                        *
 * functions.php:                                                         *
 * Miscellaneous helper functions.                                        *
 *                                                                        *
 **************************************************************************/

// Check if it's a valid geocode
function checkGeoCode($gCode)
{
	$ret = false;
	if(eregi("^[0-9]+.[0-9]*$",$gCode)) {
		$ret = true;
	}
	return $ret;
}

// Some convenience wrappers around mySQL functions
function mysqlQuery($query, $args = false)
{
	#wrapped version of mysql_query that throws exceptions
	if($args)
	{
		if(!is_array($args))
		{
			$args = array($args);
		}
		$escapedargs = array_map("mysql_real_escape_string", $args);
		$query = vsprintf($query, $escapedargs);
	}

	$result = mysql_query($query);
	
	if(!$result)
	{
		throw new Exception("mySQL says '" . mysql_error() . "' on query:\n == $query ==");
	}
	else
	{
		return $result;
	}
}

function mysqlFetchArray($result)
{
	$row = mysql_fetch_array($result);
	$error = mysql_error();
	if($error)
	{
		throw new Exception("mysql problem: $error");
	}
	return $row;
}

function mysqlResultAsArray($query)
{
	$result = mysqlQuery($query);

	$theArray = array();
	while($row = mysqlFetchArray($result))
	{
		$theArray[]= $row;
	}
	return $theArray;
}

// Grab available municipalities
function fetchMunicipalities($municipalities){
	$query = "SELECT * FROM municipalities group by name order by name";
	$result = mysqlQuery($query);
	while($row = mysqlFetchArray($result))
	{
		$municipalities[$row["id"]] = $row["name"];
	}
	return $municipalities;
}

// Find the geocode for an address
function geoCodeConvert($address) {
	if (!$address || OFFLINE)
		return;
	$address2 = urlencode($address);
	$buffer = file_get_contents("http://maps.google.com/maps/geo?output=xml&q=$address2&key=".KEY."&gl=CA");
	$sBuffer = explode("<coordinates>",$buffer);
	$sBuffer2 = explode("</coordinates>",$sBuffer[1]);
	$sBuffer3 = explode(",", $sBuffer2[0]);
	
	if($sBuffer2[0])
	{
		//location was valid
		$gCode = array("latitude"=>$sBuffer3[1] , "longitude"=>$sBuffer3[0]);
	}
	//var_dump($gCode);
	return $gCode;
}


// Dunno. something...
function getLocationQuery($filter, $municipalities){
	if(!$filter || !isset($municipalities[$filter]))
	{
		$filter = "All";
	}
	//"WHERE true" added for ease of appending strings in other queries.
	$query = "SELECT * FROM locations WHERE true";
	
	if($filter != "All")
	{
		$query .= " AND locations.municipality_id='$filter'";
	}
	return $query;
}

?>
