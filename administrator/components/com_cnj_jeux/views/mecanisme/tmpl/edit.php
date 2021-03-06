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
		if (task == 'mecanisme.cancel' || document.formvalidator.isValid(document.id('mecanisme-form'))) {
			Joomla.submitform(task, document.getElementById('mecanisme-form'));
		}
	}
</script>

<form action="<?php 
if(array_key_exists('ofrom', $_GET )) 
{
	echo JRoute::_('index.php?option=com_cnj_jeux&layout=edit&ofrom=jeu&id_mecanisme='.(int) $this->item->id_mecanisme); 
}
else
{
	echo JRoute::_('index.php?option=com_cnj_jeux&layout=edit&id_mecanisme='.(int) $this->item->id_mecanisme); 
}
?>" method="post" name="adminForm" id="mecanisme-form" class="form-validate">
	<div class="width-100 fltlft">
		<fieldset class="adminform">
			<legend><?php echo empty($this->item->id_mecanisme) ? JText::_('COM_CNJ_JEUX_NEW_mecanisme') : JText::sprintf('COM_CNJ_JEUX_DETAILS', $this->item->id_mecanisme); ?></legend>
			<ul class="adminformlist">
				<li><?php echo $this->form->getLabel('mecanisme'); ?>
				<?php 
if(empty($this->item->id_mecanisme))
{
  if(array_key_exists('newmecanisme', $_GET )) 
  {
    echo $this->form->getInput('mecanisme',NULL,$_GET['newmecanisme']); 
  }
  else
  {
    echo $this->form->getInput('mecanisme'); 
  }
}
else
{
    echo $this->form->getInput('mecanisme'); 
}

?></li>



				<li><?php echo $this->form->getLabel('id_mecanisme'); ?>
				<?php echo $this->form->getInput('id_mecanisme'); ?></li>
			</ul>
			<div class="clr"> </div>

		</fieldset>
	</div>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>

<div class="clr"></div>
</form>
