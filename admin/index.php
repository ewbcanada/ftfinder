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
 * admin/index.php:                                                       *
 * Provide dashboard for admin functions: add/modify/remove locations     *
 *                                                                        *
 **************************************************************************/

// General includes
include('../config.php');
include('../functions.php');

// Set up templating system
require_once("../smarty/Smarty.class.php");
$smarty = new Smarty();
$smarty->template_dir = '../templates';
$smarty->compile_dir = '../smarty/templates_c';
$smarty->cache_dir = '../smarty/cache';
$smarty->config_dir = '.';
$smarty->caching = 0;
$smarty->assign('base', $base);

$filter = $input;
//TODO: sanitize locSearch input
$locSearch = $_POST['locSearch'];
//TODO: sanitize locSearch input
$prodCatId = $_POST['prodCat'];

//dynamically create array for drop down list
if($filter == NULL || $filter == 'All'|| is_numeric($filter))
{
	$municipalities = array("" => "Select a city...", "All" => "All");
	$municipalities = fetchMunicipalities($municipalities);

	$smarty->assign('municipalities', $municipalities);
	$smarty->assign('id',$filter);
	if (!$locSearch){
		$query = getLocationQuery($filter, $municipalities);		
		$points = mysqlResultAsArray($query);
	} else {
		$locGCode = geoCodeConvert($locSearch);
		if($locGCode){
			//temporary
			$msg = "location search. Geocode: ".$locGCode;
		} else {
			$pointsQuery="SELECT * FROM locations WHERE MATCH (name) AGAINST ('".$locSearch."')";
			if ($prodCatId)
				$pointsQuery.="AND product_id=".$prodCatId;
			$points = mysqlResultAsArray($pointsQuery);
		}
		//temporary
		$_SESSION['msg']=$msg;
	}
	$smarty->assign('points', $points);
	
	$mun = mysqlResultAsArray("SELECT * FROM municipalities");
	$smarty->assign('mun', $mun);

	$categories = mysqlResultAsArray("SELECT * FROM categories");
	
	$products[] = "All";
	foreach ($categories as $key => $headings){
		$prodQuery = "SELECT name, id FROM products WHERE category_id='".$headings['id']."' ORDER BY name";
		//var_dump($prodQuery);
		$prodCat = mysqlResultAsArray($prodQuery);
		//var_dump($prodCat);
		foreach ($prodCat as $subheadings){
			$tempArray[$subheadings['id']] = $subheadings['name'];
		}
		$products[$headings['name']] = $tempArray;
		unset($tempArray);
	}
	$smarty->assign('prodCat', $products);
}
else
{
	echo '$filter failed validate.';
}

$smarty->assign("body", 'list.html');
$smarty->display("wrapper.html");

?>

