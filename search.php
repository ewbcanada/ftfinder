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
 * search.php:                                                            *
 * Accept a number of search parameters, and return an XML document       *
 * with locations matching the given parameters.                          *
 *                                                                        *
 **************************************************************************/

include('config.php');
include('functions.php');

// Sanitize search parameters
$locCat = mysql_real_escape_string($_GET['locCat']);
$locSearch = mysql_real_escape_string($_GET['locSearch']);
$prodCatId = mysql_real_escape_string($_GET['prodCat']);
$nameSearch = mysql_real_escape_string($_GET['nameSearch']);
$pageCurrent= mysql_real_escape_string($_GET['pnum']);
$getLat = mysql_real_escape_string($_GET['lat']);
$getLng = mysql_real_escape_string($_GET['lng']);
$getLatDiff = mysql_real_escape_string($_GET['lat2']);
$getLngDiff = mysql_real_escape_string($_GET['lng2']);

if (!$pageCurrent)
	$pageCurrent = 0;

// A few options...	
$rad = 25;
$resultsPerPage = 10;
$limit = $pageCurrent*$resultsPerPage;
	
$pointsQuery = "SELECT SQL_CALC_FOUND_ROWS";

// User entered an address: find the lat/long of their request	
if($locSearch && !($getLat && $getLng))
{
	//TODO: pass geoCode in GET data?
	$locGCode = geoCodeConvert($locSearch);
	$getLat = $locGCode['latitude'];
	$getLng = $locGCode['longitude'];
}

// Legacy.  This "if" should always return true... (I think?)
if($locSearch || ($getLat && $getLng))
{
	// Add distance criteria to query
	$pointsQuery .= " GC_DISTANCE(locations.latitude, locations.longitude, ".$getLat.", ".$getLng.") AS distance, ";
}

// Add standard location iformation to query
$pointsQuery .= " locations.id, locations.address, locations.name,";
$pointsQuery .= " locations.latitude, locations.longitude,";
$pointsQuery .= " locations.locationType_id, locations.phone, locations.website,";
$pointsQuery .= " locations.description, locations.productDescription";
$pointsQuery .= " FROM locations";

// Only include this if we're filtering by category (and using this later in the WHERE clause!)
if ($prodCatId)
	$pointsQuery .= ", locprods";

// Include a WHERE clause so it won't error if no more criteria are set
$pointsQuery .= " WHERE true";

// Add advanced search options
if ($locCat)
	$pointsQuery .= " AND locations.locationType_id='$locCat'";
if ($nameSearch)
	$pointsQuery .= " AND MATCH (locations.name) AGAINST ('".$nameSearch."')";
if ($prodCatId)
	$pointsQuery .= " AND locprods.location_id = locations.id AND locprods.product_id=".$prodCatId;

// This is used for general location searches (limit results to first 10; map will recenter)
if($locSearch && $_GET['ignorezoom'])
{
	$center = array("latitude" => $getLat, "longitude" => $getLng,  "zoom" => 12);
	$limit = 10;
}	

// Otherwise, get first 40 locations that fit on the screen.
else if ($getLat && $getLng && $getLatDiff && $getLngDiff)
{
	$pointsQuery .= " AND latitude BETWEEN " . ($getLat - $getLatDiff) . " AND " . ($getLat + $getLatDiff);
	$pointsQuery .= " AND longitude BETWEEN " . ($getLng - $getLngDiff) . " AND " . ($getLng + $getLngDiff);
	$center = array("latitude" => $getLat, "longitude" => $getLng,  "zoom" => 12);
	$limit = 40;
}

// else...? (shouldn't need one)

// Decide on sorting... by distance if doing a proximity search; by name
// if displaying all locations on screen
if($locSearch || ($getLat && $getLng))
	$pointsQuery .=" ORDER BY distance";
else 
	// Can we find the point of the map centre, and still sort by distance?
	$pointsQuery .= " ORDER BY locations.name";
		
$pointsQuery .= " LIMIT 0, ". $limit;

// Finally, run the query	
$gArray = mysqlResultAsArray($pointsQuery);
$rnum = mysqlFetchArray(mysqlQuery("SELECT FOUND_ROWS()"));

// Prepare to output as XML document
$doc = new DOMDocument('1.0');
$doc->formatOutput = true;
$node = $doc->createElement("xmlData");
$parnode = $doc->appendChild($node);

// Add pagination information (ie, if more results were available but the 
// SQL LIMIT statement kicked in...)
if($rnum[0])
{
	$node = $doc->createElement("results");
	$newnode = $parnode->appendChild($node);

	if ($limit < count($gArray))
		$newnode->setAttribute("displayed", $limit);
	else
		$newnode->setAttribute("displayed", count($gArray));

	$newnode->setAttribute("available", "" . $rnum[0]);
}

// Take results and add into XML doc
foreach ($gArray as $row)
{
	$query = "SELECT products.name FROM products, locprods 
					WHERE locprods.location_id='".$row['id']."' 
					AND locprods.product_id=products.id ORDER BY products.name";
	$products  = mysqlResultAsArray($query);
		
	// ADD TO XML DOCUMENT NODE	
	$node = $doc->createElement("marker");
	$newnode = $parnode->appendChild($node);
		
	$newnode->setAttribute("name", htmlentities($row['name']));
	$newnode->setAttribute("address", htmlentities($row['address']));
	$newnode->setAttribute("lat", $row['latitude']);
	$newnode->setAttribute("lng", $row['longitude']);
	$newnode->setAttribute("id", $row['id']);
	$newnode->setAttribute("type", $row['locationType_id']);
	$newnode->setAttribute("distance", $row['distance']);
	$newnode->setAttribute("phone", $row['phone']);
	$newnode->setAttribute("website", $row['website']);
	$newnode->setAttribute("description", $row['description']);
	$newnode->setAttribute("productDescription", $row['productDescription']);
		
	foreach($products as $product)
	{
		$node = $doc->createElement("product");
		$prodnode = $newnode->appendChild($node);
			
		$prodnode->setAttribute("name", $product['name']);
	}
	unset($products);
}

// If we need to re-center the map, add the necessary info for that.
if($center)
{
	$node = $doc->createElement("center");
	$newnode = $parnode->appendChild($node);
		
	$newnode->setAttribute("latitude", $center['latitude']);
	$newnode->setAttribute("longitude", $center['longitude']);
	$newnode->setAttribute("zoom", $center['zoom']);
	$newnode->setAttribute("range", $rad);
}

// And finally, output it all!	
echo $doc->saveXML();
?>

