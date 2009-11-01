<?php /* Smarty version 2.6.19, created on 2009-10-31 16:26:40
         compiled from view/wrapper.html */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"	xml:lang="en">
<head>

<title>The Fair Trade Finder - PlayYourPart.ca</title>

<link href="<?php echo $this->_tpl_vars['base']; ?>
/style/base-min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->_tpl_vars['base']; ?>
/style/reset-fonts.css" rel="stylesheet" type="text/css" />

<link href="<?php echo $this->_tpl_vars['base']; ?>
/style/style.css" rel="stylesheet" type="text/css" />


<script type="text/javascript">
<?php echo '

function getHeight() {
  var myWidth = 0, myHeight = 0;
  if( typeof( window.innerWidth ) == \'number\' ) {
    //Non-IE
    myWidth = window.innerWidth;
    myHeight = window.innerHeight;
  } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
    //IE 6+ in \'standards compliant mode\'
    myWidth = document.documentElement.clientWidth;
    myHeight = document.documentElement.clientHeight;
  } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
    //IE 4 compatible
    myWidth = document.body.clientWidth;
    myHeight = document.body.clientHeight;
  }
  return myHeight;
}
//alert(getHeight());

function setSiteHeight() {

	var myHeight = getHeight();
	var mapElement = document.getElementById("map");
	mapElement.style.height = 480 - (650-myHeight)+\'px\';
	var sidebarElement = document.getElementById("sidebar");
	sidebarElement.style.height = 480 - (650-myHeight)+\'px\';
	var detailsElement = document.getElementById("detailsbox");
	detailsElement.style.height = 410 - (650-myHeight)+\'px\';

}



'; ?>


</script>


<?php if ($this->_tpl_vars['mappingMode']): ?>
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php echo $this->_tpl_vars['key']; ?>
"
      type="text/javascript"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['base']; ?>
/PolylineEncoder.js"></script>
<script src="<?php echo $this->_tpl_vars['base']; ?>
/elabel.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['base']; ?>
/mapping.php?locCat=<?php echo $this->_tpl_vars['locCat']; ?>
&locSearch=<?php echo $this->_tpl_vars['locSearch']; ?>
&nameSearch=<?php echo $this->_tpl_vars['nameSearch']; ?>
&prodCat=<?php echo $this->_tpl_vars['prodCatId']; ?>
"> </script>
<?php endif; ?>
</head>

<body <?php if ($this->_tpl_vars['mappingMode']): ?> onload="setSiteHeight(); load()" onunload="GUnload()" onresize="setSiteHeight();" <?php endif; ?>>
<?php if ($this->_tpl_vars['msg']): ?>
<fieldset>
<?php echo $this->_tpl_vars['msg']; ?>

</fieldset>

<br/>
<?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['body'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>


<script src="https://ssl.google-analytics.com/urchin.js" type="text/javascript"></script>
<script type="text/javascript">
_uacct = "UA-907833-3";
urchinTracker();
</script>

</body>
</html>