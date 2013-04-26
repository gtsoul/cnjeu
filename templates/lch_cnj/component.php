<?php

/**
 * Template LCH
 * 
 * Affichage des popups
 *
 * @author LCH 
 */


// accès direct interdit
defined('_JEXEC') or die;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	
<html xmlns="http://www.w3.org/1999/xhtml" 
	  xml:lang="<?php echo $this->language; ?>" 
	  lang="<?php echo $this->language; ?>" 
	  dir="<?php echo $this->direction; ?>">
	  	
	<head>
		<jdoc:include type="head" />		
		<?php JHtml::stylesheet( 'templates/' . $this->template . '/css/template.css.php', array('media' => 'Print') ); ?>		
	</head>
	
	<body class="contentpane">
		<jdoc:include type="message" />
		<jdoc:include type="component" />
	</body>
	
</html>