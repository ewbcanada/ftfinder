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
 * import.php:                                                            *
 * Import initial data into the fair trade finder database.               *
 *                                                                        *
 * USAGE:                                                                 *
 * php import.php [spreadsheet | test ]                                   *
 *  ie php import.php spreadsheet.csv                                     *
 *       to do a clean import based on spreadsheet.csv                    *
 *                                                                        *
 *     php import.php test                                                *
 *       to re-create & populate the database with test data              *
 *                                                                        *
 **************************************************************************/

include "config.php";
include "functions.php";

// Parse parameters
if ($_SERVER['argv'][1] == 'test')
	$test = true;

else if ($_SERVER['argv'][1])
{
	$test = false;
	$csv = $_SERVER['argv'][1];
}

else (!$_SERVER['argv'][1])
{
	echo "USAGE:\n";
	echo "php import.php [spreadsheet | test ] \n";
	echo "\n";
	echo "Examples:\n";
	echo "php import.php spreadsheet.csv\n";
	echo "   to do a clean import based on spreadsheet.csv\n";
	echo "\n";
	echo "php import.php test\n";
	echo "   to re-create & populate the database with test data\n";
	die();
}

// Clear database if needed
mysqlQuery("DROP TABLE IF EXISTS municipalities, locations, products, geocodes, categories, locationType, locprods");

// Create database tables
mysqlQuery("CREATE TABLE IF NOT EXISTS `locations` (
  `id` int(11) NOT NULL auto_increment,
  `address` varchar(1024) NOT NULL,
  `name` varchar(1024) NOT NULL,
  `municipality_id` int(255) NOT NULL,
  `latitude` decimal(10,6) NOT NULL,
  `longitude` decimal(10,6) NOT NULL,
  `locationType_id` varchar(10) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `website` varchar(1024) NOT NULL,
  `description` varchar(2048) NOT NULL,
  `productDescription` varchar(1024) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `latitude` (`latitude`),
  KEY `longitude` (`longitude`),
  FULLTEXT KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
");

mysqlQuery("CREATE TABLE IF NOT EXISTS `municipalities` (
  `id` int(255) NOT NULL auto_increment,
  `name` varchar(1024) NOT NULL,
  `zoom` tinyint(4) NOT NULL,
  `latitude` decimal(10,6) NOT NULL,
  `longitude` decimal(10,6) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;");

mysqlQuery("CREATE TABLE IF NOT EXISTS `products` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(1024) NOT NULL,
  `category_id` int(10) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
");

mysqlQuery("CREATE TABLE IF NOT EXISTS `locprods` (
  `id` int(10) NOT NULL auto_increment,
  `location_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;");

mysqlQuery("INSERT INTO `products` (`id`, `name`, `category_id`) VALUES
(1, 'cocoa', 1),
(2, 'coffee', 2),
(3, 'flowers', 3),
(4, 'quinoa', 1),
(5, 'rice', 1),
(6, 'sugar', 1),
(7, 'tea', 2),
(8, 'wine', 2),
(9, 'fruit', 1),
(10, 'spices', 1),
(11, 'herbs', 1),
(12, 'honey', 1),
(13, 'cotton', 5),
(14, 'shea butter', 4),
(15, 'sports balls', 3);
");

mysqlQuery("CREATE TABLE IF NOT EXISTS `locationType` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(1024) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;");

mysqlQuery("INSERT INTO `locationType` (`id`, `name`) VALUES
(1, 'groceries'),
(2, 'cafe'),
(3, 'other');");

mysqlQuery("CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(1024) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;");

mysqlQuery("INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'food'),
(2, 'beverages'),
(3, 'recreation'),
(4, 'cosmetics'),
(5, 'clothing'),
(6, 'other');");


if ($test)
{
	include "data.php";

	foreach ($cities as $city => $geoCode)
	{
		$city2 = $city;
		$geoCode = explode(",", $geoCode);
		mysqlQuery("INSERT INTO municipalities SET name='%s', latitude='%s', longitude='%s', zoom='12'", 
					array($city, $geoCode[0], $geoCode[1]));

		$result = mysqlQuery("SELECT id FROM municipalities WHERE name='%s'", $city);
		$id = mysqlFetchArray($result);
		$id = $id[0];

		foreach($data[$city2] as $row)
		{
			$temp = explode(",", mysql_real_escape_string("$row[1]"));
			$lat = $temp[1];
			$long = $temp[0];
			$row2 = mysql_real_escape_string("$row[2]");
			$row0 = mysql_real_escape_string("$row[0]");
			$id = mysql_real_escape_string($id);
		
			$rTypeInt = rand(1,3);

			$query = "INSERT INTO locations SET latitude='".$lat."', longitude='".$long."', address='".$row2."', name='".$row0."', municipality_id='".$id."', locationType_id='".$rTypeInt."'";
			mysqlQuery($query);
		
			for($i = 0; $i < rand(1,4); $i++)
			{
				$query = "INSERT INTO locprods SET location_id='".mysql_insert_id()."', product_id='".rand(1,15)."'";
				mysqlQuery($query);
			}
		
		}
	}
}

else
{
	$failedCount = 0;
	$handle = fopen($csv, "r");

	while (($data = fgetcsv($handle)) !== FALSE)
	{
		$num = count($data);
		$query = "INSERT INTO locations SET ";
	    
		if ($data['0'])
	    	$query .= "name='".mysql_real_escape_string(utf8_decode($data['0']))."'";
	    else
	    	$failed = true;
	    if ($data['1'] && $data['2'])
		{
	    	$address = $data['1'].", ".$data['2'];
	    	$query .= ", address='".mysql_real_escape_string(utf8_decode($address))."'";
	    }
	    else 
	    	$failed = true;

	    if($data['3'])
	    	$query .= ", phone='".mysql_real_escape_string($data['3'])."'";
	    if($data['4'])
	    	$query .= ", website='".mysql_real_escape_string($data['4'])."'";
	    if($data['5'])
	    	$query .= ", description='".mysql_real_escape_string($data['5'])."'";
	    if($data['6'])
	    	$query .= ", productDescription='".mysql_real_escape_string($data['6'])."'";
	    if($data['7'])
		{
			if ($data['7'] == "Store or Supermarket")
				$query .= ", locationType_id='1'";
			else if($data['7'] == "Cafe or Restaurant")
				$query .= ", locationType_id='2'";
			else
				$query .= ", locationType_id='3'";
		}

		var_dump($address);
		$geocodes = geoCodeConvert($address);
		while (!$geocodes)
		{
			usleep(100);
			$i++;
			if ($i>10)
			{
				$failed = true;
				break;
			}
		}
		unset($i);

		$query.=", latitude='".mysql_real_escape_string($geocodes['latitude'])."', longitude='".mysql_real_escape_string($geocodes['longitude'])."'";
		if (!$failed)
		{
			//var_dump($query);
			mysqlQuery($query);
			$j = mysql_insert_id();

			if ($data['8'])
			{
				$products = explode(",", $data['8']);
				if ($products)
				{
					foreach ($products as $product)
					{
						$product = strtolower(trim($product));
						$result = mysqlQuery("SELECT id FROM products WHERE name='".$product."'");

						if (mysql_num_rows($result) == 0)
						{
							//make sure category_id for default insert corresponds to 'other'
							mysqlQuery("INSERT INTO products SET name='".$product."', category_id='6'") or die("Can't insert!!!");
							var_dump("inserting: ".$product);
							$name = mysql_insert_id();
						}
						else
							$name=mysqlFetchArray($result);
			    		  
						mysqlQuery("INSERT INTO locprods SET location_id='".$j."', product_id='".mysql_real_escape_string($name[0])."'");
					}
				}
			}
		}
		else
		{
			$failedCount++;
		}
	    
		unset($failed);
		
		sleep(1.5);
	    
	}
	var_dump($failedCount);
	fclose($handle);
}
?>

