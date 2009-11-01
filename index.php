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
 * index.php:                                                             *
 * Display the fair trade finder map.                                     *
 *                                                                        *
 **************************************************************************/

// General includes
include('config.php');
include('functions.php');

// Set up templating system
require_once("smarty/Smarty.class.php");
$smarty = new Smarty();
$smarty->template_dir = 'templates';
$smarty->compile_dir = 'smarty/templates_c';
$smarty->cache_dir = 'smarty/cache';
$smarty->config_dir = '.';
$smarty->caching = 0;
$smarty->assign('base', $base);

// Populate location types
$locCatChoice = array(	"" => "Select a Retailer Type...",
			1  => "Supermarkets",
			2  => "Caf&eacute;s / Restaurants",
			3  => "Others");

$categories = mysqlResultAsArray("SELECT * FROM categories");

// Populate products
$products[] = "Select a product...";
foreach ($categories as $heading)
{
	$prodQuery = "SELECT name, id FROM products WHERE category_id='" . $heading['id'] . "' ORDER BY name";
	$prodCat = mysqlResultAsArray($prodQuery);

	foreach ($prodCat as $subheadings)
		$tempArray[$subheadings['id']] = $subheadings['name'];

	$products[$heading['name']] = $tempArray;
	unset($tempArray);
}

// Push into template
$smarty->assign('prodCat', $products);
$smarty->assign('locCatChoice', $locCatChoice);

$smarty->assign('key', KEY);
$smarty->assign("body", 'map.html');
$smarty->display("wrapper.html");

?>
