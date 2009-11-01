<?php
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


?>
