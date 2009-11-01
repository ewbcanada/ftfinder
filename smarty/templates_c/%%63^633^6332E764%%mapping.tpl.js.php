<?php /* Smarty version 2.6.19, created on 2009-10-31 20:49:12
         compiled from mapping.tpl.js */ ?>
var gmarkers = [];
var map;
var nextPage;
var mapBounds;
var bounds;


var locSearchVar = "";
var nameSearchVar = "";
var prodCatVar = "";
var locCatVar = "";
var latClicked = "";
var lngClicked = "";

// Icon courtesy of http://www.ajaxload.info/ - they're awesome!
var loadingIcon = "<?php echo $this->_tpl_vars['base']; ?>
/icons/ajax-loader.gif";

var tinyIcon = new GIcon();
tinyIcon.image = "<?php echo $this->_tpl_vars['base']; ?>
/icons/red-dot.png";
tinyIcon.iconSize = new GSize(20, 34);
tinyIcon.iconAnchor = new GPoint(10, 34);
tinyIcon.infoWindowAnchor = new GPoint(10, 5);
markerOptions1 = { icon:tinyIcon };

var locationIcon = new GIcon();
locationIcon.image="<?php echo $this->_tpl_vars['base']; ?>
/icons/POI.png";
locationIcon.iconSize = new GSize(32,32);
locationIcon.iconAnchor = new GPoint(25,25);
locationIcon.infoWindowAnchor = new GPoint(10,5)
markerOptions2 = { icon:locationIcon } ;


function loadData(){
	latClicked = "";
	lngClicked = "";
	locSearchVar = document.getElementsByName("locSearch");
	if(locSearchVar[0].value && locSearchVar[0].value != "Enter an address here")
		locSearchVar = locSearchVar[0].value;
	else
		locSearchVar = "";
	nameSearchVar = document.getElementsByName("nameSearch");
	if(nameSearchVar[0].value && nameSearchVar[0].value != "Search for name of retailer here")
		nameSearchVar = nameSearchVar[0].value;
	else
		nameSearchVar = "";
	prodCatVar = document.getElementsByName("prodCat");
	if(prodCatVar[0].value && prodCatVar[0].value != 0)
		prodCatVar = prodCatVar[0].value;
	else
		prodCatVar = "";
	locCatVar = document.getElementsByName("locCat");
	if(locCatVar[0].value)
		locCatVar = locCatVar[0].value;
	else
		locCatVar = "";
	
	if(!locCatVar && !locSearchVar && !prodCatVar && !nameSearchVar)
		return;

	//alert(locSearchVar + " 2: " + nameSearchVar + " 3: " + prodCatVar + " 4: " + locCatVar);

	refreshMap(true);
}

function createLocMarker(point){
	var tMarker = new GMarker(point, markerOptions2);
	return tMarker;
}

function createBpMarker(point, name, address, id, markerType, products, colour, number, details, distance) 
{
	var iconPath = "<?php echo $this->_tpl_vars['base']; ?>
/icons/" + colour + "_Marker";
	if (parseInt(number) < 26)
		iconPath += String.fromCharCode(0x41 + parseInt(number));
	iconPath += ".png";

	var tMarker = new GMarker(point, markerType);
	tMarker.getIcon().image = iconPath;
	var data = "<div class=\"popupinfoboxes\">";
	data += "<div class=\"infoTitles\">" +name + "</div>";
	data += address + "<br />";
	for(var i=0; i<details.length; i++){
		data+= details[i] + "<br />";
	}
	if(distance)
		data += distance + " km <br />"; 
	data += "<div class=\"infobox-products-carried\">Products: <ul>";
	for(var i=0; i<products.length; i++){
		data+= "<li>" + products[i] + "</li>";
	}
	data +=	"</ul></div></div>";
	GEvent.addListener(tMarker, "click", function() {tMarker.openInfoWindowHtml(data);});
	
	gmarkers[id] = tMarker;
	
	var detailsBox = '<div><br />';
	detailsBox += '<a href="javascript:myclick(' + id + ')" class="infoTitles">';
	detailsBox += '<img class="iconImage" src="' + tMarker.getIcon().image + '"/>';
	detailsBox +=  name + '</a><br />';
	detailsBox += '<div class="infoboxes">';
	detailsBox += address + '<br />';
	for(var i=0; i<details.length; i++){
		detailsBox+= details[i] + "<br />";
	}
	if (distance)
		detailsBox += distance + ' km (approximately)<br />';
	detailsBox += '</div></div>';
	
	document.getElementById('detailsbox').innerHTML += detailsBox;
	
	return tMarker;
}


function myclick(id) {
    GEvent.trigger(gmarkers[id], "click");
}

function xmlQuery(){
	return "<?php echo $this->_tpl_vars['base']; ?>
/xmlGen.php?locCat="+locCatVar+"&locSearch="+locSearchVar
			+"&nameSearch="+nameSearchVar+"&prodCat="+prodCatVar;
}

function loadXML(data, isSearch){
	if (isSearch === undefined)
		isSearch = true;

	//GLog.write("starting AJAX event");
	var xml = GXml.parse(data);
	var center = xml.documentElement.getElementsByTagName("center")[0];

	var maxLat = 0;
	var maxLng = 0;
	var centerPoint;
	var bounds = new GLatLngBounds();

	if (center != undefined && isSearch) {
		var longitude = center.getAttribute("longitude");
		var latitude = center.getAttribute("latitude");
		var zoom = center.getAttribute("zoom");
		var range = center.getAttribute("range");
		//map.panTo(new GLatLng(latitude, longitude));
		centerPoint = new GLatLng(parseFloat(latitude), parseFloat(longitude)); 
		var centerMarker = createLocMarker(centerPoint);
		bounds.extend(centerPoint);
		map.addOverlay(centerMarker);
	}

	var results = xml.documentElement.getElementsByTagName("results")[0];
	if(results != undefined){
		var resultsDisplayed = parseInt(results.getAttribute("displayed"));
		var resultsAvailable = parseInt(results.getAttribute("available"));

		var locType = "locations";
		if (locCatVar == 1)
			locType = "<strong>supermarkets</strong>";
		else if (locCatVar == 2)
			locType = "<strong>restaurants</strong>";

		var prodType = "";
		<?php $_from = $this->_tpl_vars['prodCatList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['prodid'] => $this->_tpl_vars['product']):
?>
		if (prodCatVar == '<?php echo $this->_tpl_vars['prodid']; ?>
')
			prodType = " selling <strong><?php echo $this->_tpl_vars['product']; ?>
</strong>";
		<?php endforeach; endif; unset($_from); ?>

		var resultString = '<span class="displayCount">';
		if (isSearch) {
			resultString = 'Displaying 10 closest ' + locType + ' ' + prodType;
			document.getElementById('more-results').style.display = "none";
		} else if (resultsAvailable > resultsDisplayed) {
			resultString = 'Displaying first ' + resultsDisplayed + ' ' + locType + ' ' + prodType + ' (' + resultsAvailable + ' available)<br/>';
			resultString += '<em>Zoom in for more results!</em>';
			document.getElementById('more-results').style.display = "block";
		} else {
			resultString = 'Found ' + resultsDisplayed + ' ' + locType + ' ' + prodType;
			document.getElementById('more-results').style.display = "none";
		}
		resultString += '</span>';

		document.getElementById('detailsbox').innerHTML += resultString;
	}

	var markers = xml.documentElement.getElementsByTagName("marker");
	var point;

	if (markers[0] != undefined){
		for (var i = 0; i < markers.length && i < 40; i++) {
			var name = markers[i].getAttribute("name");
			var address = markers[i].getAttribute("address");
			var lat = parseFloat(markers[i].getAttribute("lat"));
			var lng = parseFloat(markers[i].getAttribute("lng"));
			if(lat && lng)
				point = new GLatLng(lat,lng);
			var id = markers[i].getAttribute("id");
			var distance = parseFloat(markers[i].getAttribute("distance"));
			var details = [];
			var phone = markers[i].getAttribute("phone");
			if(phone)
				details.push(phone);
			var website = markers[i].getAttribute("website");
			if(website)
				details.push('<a href="' + website + '" target="_blank">' + website + '</a>');
			var description = markers[i].getAttribute("description");
			if(description)
				details.push(description);			
			var type = markers[i].getAttribute("type");
			if (type == 1)
				colour = "red";
			else if (type == 2)
				colour = "blue";
			else
				colour = "yellow";
				
			var products = [];
			var children = markers[i].getElementsByTagName("product");
			for (var j=0; j<children.length; j++){
				products.push(children[j].getAttribute("name")); 
			}
			if(distance && isSearch)
		 		var marker = createBpMarker(point, name, address, id, markerOptions1, products, colour, i, details, distance.toFixed(2));
		 	else
		 		var marker = createBpMarker(point, name, address, id, markerOptions1, products, colour, i, details);
		 	if(marker){
		 		map.addOverlay(marker);
		 	}

			if (isSearch)
				bounds.extend(point);
		}

	} else {
		document.getElementById('detailsbox').innerHTML = "The search returned no results. Please try another address.";
	}

	if (isSearch) {
		map.setZoom(map.getBoundsZoomLevel(bounds));
		map.panTo(bounds.getCenter());
	}
}

function setupPoints(map){
	//GLog.write("setupPoints function called");
	
	GDownloadUrl(xmlQuery(), function(data){
		loadXML(data);
	});
	
	
/*    
    GEvent.addListener(map, "move", function() {
	    if(mapBounds == undefined)
	    	return;
    	checkBounds();
    });
*/    //GLog.write("Markers added");
}

/**
 * Grab a new list of fair trade locations and display on map
 *
 * Meant to be called when a map event has ocurred (ie map moved or zoom changed): it 
 * queries the server for an updated list of locations based on the map's current position,
 * and displays the results
 *
 * @param ignoreZoom  If true, the map's current zoom is ignored and the map is resized based
 *                    on the first ten results.
 */
function refreshMap(isSearch)
{
	var query = xmlQuery();

	if (isSearch) {
		query += "&ignorezoom=yes";
	} else {
		// Get the map's current centre point
		latClicked = map.getCenter().lat();
		lngClicked = map.getCenter().lng();

		// Calculate size of map (ie, distance from centre to the top & side)
		var lat2 = map.getBounds().getNorthEast().lat() - latClicked;
		var lng2 = map.getBounds().getNorthEast().lng() - lngClicked;

		// Form query to send to server
		query += "&lat=" + latClicked + "&lng=" + lngClicked + "&lat2=" + lat2 + "&lng2=" + lng2;
	}

	// Display "loading" screen
	map.clearOverlays();

	var shadeOverlay = new GPolygon([
		map.getBounds().getNorthEast(),
		new GLatLng(map.getBounds().getSouthWest().lat(), map.getBounds().getNorthEast().lng()),
		map.getBounds().getSouthWest(),
		new GLatLng(map.getBounds().getNorthEast().lat(), map.getBounds().getSouthWest().lng()),
		map.getBounds().getNorthEast()
	], "ffffff", 0, 0, "ffffff", 0.3);
	map.addOverlay(shadeOverlay);

	var loading = new GIcon();
	loading.image = loadingIcon;
	loading.iconSize = new GSize(100, 100);
	loading.iconAnchor = new GPoint(50, 50);
	markerOptionsLoading = { icon:loading };
	map.addOverlay(new GMarker(map.getCenter(), markerOptionsLoading));

	// Query server for updated results
    	GDownloadUrl(query, function(data) {
			if (isSearch) {
				GEvent.clearListeners(map, "dragend");
				GEvent.clearListeners(map, "zoomend");
			}

			map.clearOverlays();
			document.getElementById('detailsbox').innerHTML = "";
			loadXML(data, isSearch);

			if (isSearch) {
			        GEvent.addListener(map, "dragend", function() { refreshMap(false) });
        			GEvent.addListener(map, "zoomend", function() { refreshMap(false) });
			}
	});
}

/**
 * Initialize and set up fair trade map
 *
 * This function is called once the page has finished loading, and is used to set up the various options
 * and callbacks for the fair trade map.
 */
function load() {

    if (GBrowserIsCompatible()) {

        map = new GMap2(document.getElementById("map"));

        // Uhh, what's this do...?  I can't find the icon?
	document.getElementById("map").style.backgroundImage = "url(icons/bigrotation2.gif)";

        // Set map options
        map.enableScrollWheelZoom();
        map.addControl(new GLargeMapControl());

        //map.enableContinuousZoom();
        //map.setMapType(G_HYBRID_MAP);

        // Initialize map to all of Canada
        map.setCenter(new GLatLng(55.468589, -91.318359), 4);

        // Add listeners for map movement & zoom chagges      
        GEvent.addListener(map, "dragend", function() { refreshMap(false) });
        GEvent.addListener(map, "zoomend", function() { refreshMap(false) });
            // How can I pass showResults() directly as the callback, without needing to create a wrapper?
    	
    } else {
        map.innerHTML = "Sorry, your browser is not able to display the Fair Trade Finder.";
    }
}