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
		if (task == 'motcle.cancel' || document.formvalidator.isValid(document.id('motcle-form'))) {
			Joomla.submitform(task, document.getElementById('motcle-form'));
		}
	}
</script>

<form action="<?php 
if(array_key_exists('ofrom', $_GET )) 
{
	echo JRoute::_('index.php?option=com_cnj_jeux&layout=edit&ofrom=jeu&id_motcle='.(int) $this->item->id_motcle); 
}
else
{
	echo JRoute::_('index.php?option=com_cnj_jeux&layout=edit&id_motcle='.(int) $this->item->id_motcle); 
}
?>" method="post" name="adminForm" id="motcle-form" class="form-validate">
	<div class="width-100 fltlft">
		<fieldset class="adminform">
			<legend><?php echo empty($this->item->id_motcle) ? JText::_('COM_CNJ_JEUX_NEW_MOTCLE') : JText::sprintf('COM_CNJ_JEUX_DETAILS', $this->item->id_motcle); ?></legend>
			<ul class="adminformlist">
				<li><?php echo $this->form->getLabel('motcle'); ?>
				<?php 
if(empty($this->item->id_motcle))
{
  if(array_key_exists('newmotcle', $_GET )) 
  {
    echo $this->form->getInput('motcle',NULL,$_GET['newmotcle']); 
  }
  else
  {
    echo $this->form->getInput('motcle'); 
  }
}
else
{
    echo $this->form->getInput('motcle'); 
}

?></li>



				<li><?php echo $this->form->getLabel('id_motcle'); ?>
				<?php echo $this->form->getInput('id_motcle'); ?></li>
			</ul>
			<div class="clr"> </div>

		</fieldset>
	</div>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>

<div class="clr"></div>
</form>
