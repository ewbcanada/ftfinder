<?php /* Smarty version 2.6.19, created on 2009-11-01 13:57:52
         compiled from map.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'map.html', 84, false),)), $this); ?>

<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php echo $this->_tpl_vars['key']; ?>
" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['base']; ?>
/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['base']; ?>
/js/mapping.js"></script>

<?php echo '
<script type="text/javascript">
	function clickclear(thisfield, defaulttext)
	{
		if (thisfield.value == defaulttext)
			thisfield.value = "";
	}

	function clickrecall(thisfield, defaulttext)
	{
		if (thisfield.value == "")
			thisfield.value = defaulttext;
	}

	function setSiteHeight()
	{
		var myHeight = $(window).height();
		$(\'#map\').height(480 - (650-myHeight));
		$(\'#sidebar\').height(480 - (650-myHeight));
		$(\'#detailsbox\').height(410 - (650-myHeight));
	}

	$(document).ready(function() {
		setSiteHeight();
		load();
	});

	$(window).resize(setSiteHeight);
	$(document).unload(GUnload);
</script>
'; ?>


<div id="top">

	(you could insert a logo here)

	<div id="inputTable">
		<div id="top-height-hack"></div>

				<form id="search-form" onsubmit="loadData(); return false">
			<div class="example"> Enter an address, intersection, or city. E.g. "366 Adelaide Street West, Toronto" <br/>
				<input type="text" size="50" class="textbox main" value="<?php if ($this->_tpl_vars['locSearch']): ?><?php echo $this->_tpl_vars['locSearch']; ?>
<?php else: ?>Enter an address here<?php endif; ?>" onclick="clickclear(this, 'Enter an address here')" onblur="clickrecall(this,'Enter an address here')" name="locSearch"/>
				<input type="submit" class="submitbutton" value="Search"/>
				<span id="advancedSearchLink" <?php if ($this->_tpl_vars['advancedMode']): ?>style="display:none"<?php endif; ?>>
					Want more search options? Try the 
					<a href="#" onClick="$('#advancedSearch').show(); $('#advancedSearchLink').hide(); $('#basicSearchLink').show()">Advanced Search</a>
				</span>
				<span id="basicSearchLink" <?php if (! $this->_tpl_vars['advancedMode']): ?>style="display:none"<?php endif; ?>>
					Too many search options? Try the
					<a href="#" onClick="$('#advancedSearch').hide(); $('#advancedSearchLink').show(); $('#basicSearchLink').hide(); $('#searchProdCat').val(0); $('#searchLocCat').val('');">Basic Search</a>
				</span>
			</div>
			<div id="advancedSearch" style="<?php if (! $this->_tpl_vars['advancedMode']): ?>display:none;<?php endif; ?> text-align:left">
				<select name="prodCat" class="selectbox" id="searchProdCat">
					<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['prodCat'],'selected' => $this->_tpl_vars['prodCatId']), $this);?>

				</select> 
				<select name="locCat" class="selectbox" id="searchLocCat">
					<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['locCatChoice'],'selected' => $this->_tpl_vars['locCat']), $this);?>

				</select>
				<input type="text" class="textbox retailers" size="50" value="<?php if ($this->_tpl_vars['nameSearch']): ?><?php echo $this->_tpl_vars['nameSearch']; ?>
<?php else: ?>Search for name of retailer here<?php endif; ?>" onclick="clickclear(this, 'Search for name of retailer here')" onblur="clickrecall(this,'Search for name of retailer here')" name="nameSearch"/>
				<br/>
			</div>		
		</form>
	</div>

</div>


<div id="main">

	<div id="sidebar">
		<div id="sidebarcontents">
			<div id="detailsbox">
				<p>To find locations near you, enter your address in the search box above.</p>
			</div>
		</div>
	</div>

	<div id="map"></div>

	<div id="more-results">
		Additional locations available - zoom in to see more accurate results!
	</div>

</div>
    
<div id="hellofrom">
	Developed by <a href="http://ewb.ca" target="_blank">Engineers Without Borders Canada</a>. <br/>
	Visit <a href="http://playyourpart.ca">PlayYourPart.ca</a>. 
</div>

<div id="bottomboxes">
	<div id="legendbox">
		<?php echo $this->_tpl_vars['locCatChoice'][1]; ?>
: <img class="legendIcon" src="<?php echo $this->_tpl_vars['base']; ?>
/icons/red_Marker.png" />
		<?php echo $this->_tpl_vars['locCatChoice'][2]; ?>
: <img class="legendIcon" src="<?php echo $this->_tpl_vars['base']; ?>
/icons/blue_Marker.png" />
		<?php echo $this->_tpl_vars['locCatChoice'][3]; ?>
: <img class="legendIcon" src="<?php echo $this->_tpl_vars['base']; ?>
/icons/yellow_Marker.png" />
	</div>

	</div>
