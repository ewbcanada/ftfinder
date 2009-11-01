<?php /* Smarty version 2.6.19, created on 2009-11-01 13:54:38
         compiled from wrapper.html */ ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"	xml:lang="en">
<head>

<title>Title</title>

<link href="<?php echo $this->_tpl_vars['base']; ?>
/style/base-min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->_tpl_vars['base']; ?>
/style/reset-fonts.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->_tpl_vars['base']; ?>
/style/style.css" rel="stylesheet" type="text/css" />

</head>

<body>

<?php if ($this->_tpl_vars['msg']): ?>
	<fieldset><?php echo $this->_tpl_vars['msg']; ?>
</fieldset>
	<br/>
<?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['body'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

</body>
</html>
