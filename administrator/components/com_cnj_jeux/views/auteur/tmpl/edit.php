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
		if (task == 'auteur.cancel' || document.formvalidator.isValid(document.id('auteur-form'))) {
			Joomla.submitform(task, document.getElementById('auteur-form'));
		}
	}
</script>
<!-- CNJ ajout pour autocompletion -->
<script type="text/javascript" src="../media/system/js/prototype.js"></script>
<script type="text/javascript" src="../media/system/js/effects.js"></script>
<script type="text/javascript" src="../media/system/js/controls.js"></script>  
<!-- !CNJ -->
<script src="components/com_cnj_jeux/com_cnj_jeux.js" type="text/javascript"></script>
<form action="<?php 
if(array_key_exists('ofrom', $_GET )) 
{
	echo JRoute::_('index.php?option=com_cnj_jeux&layout=edit&ofrom=jeu&id_auteur='.(int) $this->item->id_auteur); 
}
else
{
	echo JRoute::_('index.php?option=com_cnj_jeux&layout=edit&id_auteur='.(int) $this->item->id_auteur); 
}
?>" method="post" name="adminForm" id="auteur-form" class="form-validate">
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo empty($this->item->id_auteur) ? JText::_('COM_CNJ_JEUX_NEW_AUTEUR') : JText::sprintf('COM_CNJ_JEUX_DETAILS', $this->item->id_auteur); ?></legend>
			<ul class="adminformlist">
				<li><?php echo $this->form->getLabel('nom'); ?>
				<?php 
if(empty($this->item->id_auteur))
{
  if(array_key_exists('newauteurs', $_GET )) 
  {
    echo $this->form->getInput('nom',NULL,$_GET['newauteurs']); 
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

?>

</li>
                                
				<li><?php echo $this->form->getLabel('alias'); ?>
				<?php echo $this->form->getInput('alias'); ?></li>
                                
				<li><?php echo $this->form->getLabel('nationalite'); ?>
				<?php echo $this->form->getInput('nationalite'); ?></li>
                                
				<li><?php echo $this->form->getLabel('presentation'); ?>
				<?php echo $this->form->getInput('presentation'); ?></li>
                                
				<li><?php echo $this->form->getLabel('site'); ?>
				<?php echo $this->form->getInput('site'); ?></li>
                                
				<li><?php echo $this->form->getLabel('transfert_createur'); ?>
				<?php echo $this->form->getInput('transfert_createur'); ?></li>

				<li><?php echo $this->form->getLabel('id_auteur'); ?>
				<?php echo $this->form->getInput('id_auteur'); ?></li>

				<li><?php echo $this->form->getLabel('tri_1'); ?>
				<?php echo $this->form->getInput('tri_1'); ?></li>
	
				<li><?php echo $this->form->getLabel('tri_2'); ?>
				<?php echo $this->form->getInput('tri_2'); ?></li>
				
				<li><?php echo $this->form->getLabel('tri_3'); ?>
				<?php echo $this->form->getInput('tri_3'); ?></li>
				
				<li><?php echo $this->form->getLabel('tri_4'); ?>
				<?php echo $this->form->getInput('tri_4'); ?></li>


			</ul>
			<div class="clr"> </div>

		</fieldset>
	</div>
    
        <div class="width-40 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::sprintf('COM_CNJ_JEUX_ASSOCIATIONS'); ?></legend>
			<ul class="adminformlist">                                
				<li><?php echo $this->form->getLabel('documents'); ?>
				<?php echo $this->form->getInput('documents'); ?></li>
			</ul>
			<div class="clr"> </div>

		</fieldset>
	</div>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>

<div class="clr"></div>
</form>
