<?php /* Smarty version 2.6.19, created on 2009-10-31 16:26:41
         compiled from view/list.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'view/list.html', 46, false),)), $this); ?>
<form method="post" action="<?php echo $this->_tpl_vars['base']; ?>
/view/dispMap">
	<input type="submit" value="View Map"/>
</form>

<fieldset>
<legend> 
	Municipality Database
</legend>

<table cellpadding="2">
	<?php $_from = $this->_tpl_vars['mun']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['mun']):
?> 
		<tr>
			<td>
			<?php echo $this->_tpl_vars['mun']['name']; ?>

			</td>
			<td> 
			<?php echo $this->_tpl_vars['mun']['geoCode']; ?>

			</td>
			<td> 
			<?php echo $this->_tpl_vars['mun']['zoom']; ?>

			</td>
			<td> 
			<form method="post" action="<?php echo $this->_tpl_vars['base']; ?>
/view/editMunicipality/<?php echo $this->_tpl_vars['mun']['id']; ?>
">
			<input type="submit" value="edit"/>
			</form>
			</td>
			<td>
			<form method="post" action="<?php echo $this->_tpl_vars['base']; ?>
/action/deleteMunicipality/"> 
			<input type="submit" value="delete"/> 
			<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['mun']['id']; ?>
"/> 
			</form>
			</td>
		</tr>
	<?php endforeach; endif; unset($_from); ?> 
</table>
<form method="post" action="<?php echo $this->_tpl_vars['base']; ?>
/view/editMunicipality/new">
<input type="submit" value="Add Municipality">
</form>
</fieldset>


<fieldset>
<legend>Location Database</legend>
<form method="post" action="<?php echo $this->_tpl_vars['base']; ?>
/view/list/">
	<select name="prodCat">
		<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['prodCat']), $this);?>

	</select> 
	<input type="text" size="50" value="search for location here" name="locSearch"/>
	<input type="submit" value="search"/>
</form>
<select onchange="window.location = '<?php echo $this->_tpl_vars['base']; ?>
/view/list/' + this.value;">
	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['municipalities'],'selected' => $this->_tpl_vars['id']), $this);?>

</select>

<table cellpadding="2"> 
	<?php $_from = $this->_tpl_vars['points']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['point']):
?> 
		<tr>
			<td>
			<?php echo $this->_tpl_vars['point']['geoCode']; ?>

			</td>
			<td> 
			<?php echo $this->_tpl_vars['point']['name']; ?>

			</td>
			<td> 
			<?php echo $this->_tpl_vars['point']['address']; ?>

			</td>
			<td> 
			<form method="post" action="<?php echo $this->_tpl_vars['base']; ?>
/view/editLocation/<?php echo $this->_tpl_vars['point']['id']; ?>
">
			<input type="submit" value="edit"/>
			</form>
			</td>
			<td>
			<form method="post" action="<?php echo $this->_tpl_vars['base']; ?>
/action/deleteLocation/"> 
			<input type="submit" value="delete"/> 
			<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['point']['id']; ?>
"/> 
			</form>
			</td>
		</tr>
	<?php endforeach; endif; unset($_from); ?> 
</table>  

<table>
<tablebody>
<tr>
<td>
<form method="post" action="<?php echo $this->_tpl_vars['base']; ?>
/view/editLocation">
<input type="submit" value="Add Location">
</form>
</td>
</tr>
</tablebody>
</table>

</fieldset>
</script>