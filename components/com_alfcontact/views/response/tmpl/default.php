<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
	
	$app = JFactory::getApplication();
	$params = JComponentHelper::getParams('com_alfcontact');
	$this->custom_header = $params->get('custom_header', '');
	$this->custom_text = $params->get('custom_text', '');
?>

<div class="item-page">
	<h2><a href="">
		<?php echo $this->custom_header; ?>
	</a></h2>
	<div class="clr"></div>
	<p style="text-align: justify;"><span class="content_table">
		<?php echo $this->custom_text; ?>
	</span></p>
</div>
