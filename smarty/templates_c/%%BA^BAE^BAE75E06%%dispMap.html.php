<?php /* Smarty version 2.6.19, created on 2009-10-31 16:26:49
         compiled from view/dispMap.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'view/dispMap.html', 37, false),)), $this); ?>
<script type="text/javascript">
<?php echo '
function clickclear(thisfield, defaulttext) {
	if (thisfield.value == defaulttext) {
	thisfield.value = "";
	}
}
function clickrecall(thisfield, defaulttext) {
	if (thisfield.value == "") {
	thisfield.value = defaulttext;
	}
}
'; ?>

</script>


<div id="top">

	<div id="inputTable">
	<div id="top-height-hack"></div>

	<form  onsubmit="loadData(); return false">
		<div class="example"> Enter an address, intersection, or city. E.g. "366 Adelaide Street West, Toronto" <br/>
		<input type="text" size="50" class="textbox main" value="<?php if ($this->_tpl_vars['locSearch']): ?><?php echo $this->_tpl_vars['locSearch']; ?>
<?php else: ?>Enter an address here<?php endif; ?>" onclick="clickclear(this, 'Enter an address here')" onblur="clickrecall(this,'Enter an address here')" name="locSearch"/>
		<input type="submit" class="submitbutton" value="Search"/>
		<span id="advancedSearchLink" <?php if ($this->_tpl_vars['advancedMode']): ?>style="display:none"<?php endif; ?>>
			Want more search options? Try the 
			<a href="#" onClick="document.getElementById('advancedSearch').style.display='block'; document.getElementById('advancedSearchLink').style.display='none'; document.getElementById('basicSearchLink').style.display='inline'">Advanced Search</a>
		</span>
		<span id="basicSearchLink" <?php if (! $this->_tpl_vars['advancedMode']): ?>style="display:none"<?php endif; ?>>
			Too many search options? Try the
			<a href="#" onClick="document.getElementById('advancedSearch').style.display='none'; document.getElementById('advancedSearchLink').style.display='inline'; document.getElementById('basicSearchLink').style.display='none'; document.getElementById('searchProdCat').value='0'; document.getElementById('searchLocCat').value='';">Basic Search</a>
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

</div><!--End of top section-->


<div id="main">

<div id="sidebar">
<div id="sidebarcontents">
    <div id="detailsbox"><p>To find Fair Trade locations near you, enter your address in the search box above.</p><br/><p>You can find Fair Trade coffeeshops, restaurants, grocery stores, office distributors and more!</p><br/><p>Thanks for choosing Fair Trade - and for making a difference in the lives of farmers around the world with your purchase.</div>
    <div class="noticeBox"><img src="<?php echo $this->_tpl_vars['base']; ?>
/style/micro-ftc.png" style="float:right;" alt="Look for the logo!" title="Look for the Logo!"/>All locations carry products certified Fair Trade by TransFair Canada.</div>
</div></div>

	<div id="map"></div>
	<div id="more-results">Additional locations available - zoom in to see more accurate results!</div>

</div>
    
<div id="hellofrom">
Brought to you by <a href="http://ewb.ca" target="_blank">Engineers Without Borders Canada</a> in association with <a href="http://transfair.ca" target="_blank">TransFair Canada</a>. For more information on Fair Trade and the difference you can make, visit <a href="http://playyourpart.ca">PlayYourPart.ca</a>. <a href="mailto:feedback@playyourpart.ca" title="Stores or products missing, questions or comments? Thanks for letting us know!">Feedback?</a><!-- Stores or products missing? <a href="mailto:feedback@playyourpart.ca">Let us know!</a> -->
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