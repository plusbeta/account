<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php echo $html->charset(); ?>
<title>
	<?php __('請求管理システム'); ?>
</title>
<?php
	echo $html->meta('icon')."\n";
	echo $html->css('reset')."\n";
	echo $html->css('user')."\n";
	echo $html->css('jquery.autocomplete')."\n";
	echo $javascript->link('jquery')."\n";
	echo $javascript->link('jquery.autocomplete')."\n";
	echo $javascript->link('util')."\n";
?>

</head>
<body>
<div id="container">

<?php echo $this->element('header'); ?>
<!-- main -->  
<?php echo $content_for_layout; ?>
<!-- /main -->  
<!-- footer --> 
<?php echo $this->element('footer'); ?>
<!-- /footer -->
</div>
</body>
</html>