<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_cnj_jeux
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'distinction.cancel' || document.formvalidator.isValid(document.id('distinction-form'))) {
			Joomla.submitform(task, document.getElementById('distinction-form'));
		}
	}
</script>

<form action="<?php 
if(array_key_exists('ofrom', $_GET )) 
{
	echo JRoute::_('index.php?option=com_cnj_jeux&layout=edit&ofrom=jeu&id_distinction='.(int) $this->item->id_distinction); 
}
else
{
	echo JRoute::_('index.php?option=com_cnj_jeux&layout=edit&id_distinction='.(int) $this->item->id_distinction); 
}
?>" method="post" name="adminForm" id="distinction-form" class="form-validate">
	<div class="width-100 fltlft">
		<fieldset class="adminform">
			<legend><?php echo empty($this->item->id_distinction) ? JText::_('COM_CNJ_JEUX_NEW_DISTINCTION') : JText::sprintf('COM_CNJ_JEUX_DETAILS', $this->item->id_distinction); ?></legend>
			<ul class="adminformlist">
				<li><?php echo $this->form->getLabel('nom'); ?>
				<?php 
if(empty($this->item->id_distinction))
{
  if(array_key_exists('newdistinction', $_GET )) 
  {
    echo $this->form->getInput('nom',NULL,$_GET['newdistinction']); 
  }
  else
  {
    echo $this->form->getInput('nom'); 
  }
}
else
{
    echo $this->form->getInput('nom'); 
}

?></li>



				<li><?php echo $this->form->getLabel('id_distinction'); ?>
				<?php echo $this->form->getInput('id_distinction'); ?></li>
			</ul>
			<div class="clr"> </div>

		</fieldset>
	</div>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>

<div class="clr"></div>
</form>
