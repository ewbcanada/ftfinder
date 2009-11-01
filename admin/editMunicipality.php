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

if($input && is_numeric($input)) //TODO: sanitize form
{	
	$rowID = $input;
	
	$municipality = mysqlFetchArray(mysqlQuery("SELECT * FROM municipalities WHERE id='%s'", $rowID));
	$smarty->assign("municipality", $municipality);
	$smarty->assign("input", $input);

}

$smarty->assign("body", 'list.html');
$smarty->display("wrapper.html");

?>
