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
		if (task == 'document.cancel' || document.formvalidator.isValid(document.id('document-form'))) {
			Joomla.submitform(task, document.getElementById('document-form'));
		}
	}
</script>

<form action="<?php 
if(array_key_exists('ofrom', $_GET )) 
{
   	echo JRoute::_('index.php?option=com_cnj_jeux&layout=edit&ofrom=jeu&id='.(int) $this->item->id); 
}
else
{
   	echo JRoute::_('index.php?option=com_cnj_jeux&layout=edit&id='.(int) $this->item->id); 
}
?>" method="post" name="adminForm" id="document-form" class="form-validate" enctype="multipart/form-data">
	<div class="width-<?php echo ($this->form->getValue('type') == 'JPG' ? '60' : '100'); ?> fltlft">
		<fieldset class="adminform">
			<legend><?php echo empty($this->item->id) ? JText::_('COM_CNJ_JEUX_NEW_DOCUMENT') : JText::sprintf('COM_CNJ_JEUX_DETAILS', $this->item->id); ?></legend>
			<ul class="adminformlist">
				<li><?php echo $this->form->getLabel('nom'); ?>
<?php 
if(empty($this->item->id))
{
  if(array_key_exists('newdocument', $_GET )) 
  {
    echo $this->form->getInput('nom',NULL,$_GET['newdocument']); 
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
                                
				<li><?php echo $this->form->getLabel('visuel'); ?>
				<input type="file" id="upload-visuel" name="visuel" /></li>
                                
				<li><?php echo $this->form->getLabel('path_hd'); ?>
				<?php echo $this->form->getInput('path_hd'); ?></li>
                                
				<li><?php echo $this->form->getLabel('path_miniature'); ?>
				<?php echo $this->form->getInput('path_miniature'); ?></li>
                                
				<li><?php echo $this->form->getLabel('path_optimise'); ?>
				<?php echo $this->form->getInput('path_optimise'); ?></li>
                                
				<li><?php echo $this->form->getLabel('commentaire'); ?>
				<?php echo $this->form->getInput('commentaire'); ?></li>
 	
				<li><?php echo $this->form->getLabel('force_telechargement'); ?>
				<?php echo $this->form->getInput('force_telechargement'); ?></li>
 

			
                               
				<li><?php echo $this->form->getLabel('type'); ?>
				<?php echo $this->form->getInput('type'); ?></li>
                                
				<li><?php echo $this->form->getLabel('taille'); ?>
				<?php echo $this->form->getInput('taille'); ?></li>

				<li><?php echo $this->form->getLabel('id_archive'); ?>
				<?php echo $this->form->getInput('id_archive'); ?></li>

				<li><?php echo $this->form->getLabel('old_id_document'); ?>
				<?php echo $this->form->getInput('old_id_document'); ?></li>

				<li><?php echo $this->form->getLabel('id'); ?>
				<?php echo $this->form->getInput('id'); ?></li>

			</ul>
			<div class="clr"> </div>

		</fieldset>
	</div>
    
        <?php if($this->form->getValue('type') == 'JPG') { ?>
        <div class="width-40 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::sprintf('COM_CNJ_JEUX_DETAILS'); ?></legend>
			<ul class="adminformlist">
                                
                            <li><?php if($this->form->getValue('path_hd')) echo $this->form->getLabel('path_hd') . '<br />' . JHtml::_('image', $this->form->getValue('path_hd'), '', array('height' => 200)); ?></li>
                            <li><?php if($this->form->getValue('path_miniature')) echo $this->form->getLabel('path_miniature') . '<br />' . JHtml::_('image', $this->form->getValue('path_miniature'), '', array()); ?></li>
                            <li><?php if($this->form->getValue('path_optimise')) echo $this->form->getLabel('path_optimise') . '<br />' . JHtml::_('image', $this->form->getValue('path_optimise'), '', array('height' => 200)); ?></li>
                                
			</ul>
			<div class="clr"> </div>

		</fieldset>
	</div>
        <?php } ?>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>

<div class="clr"></div>
</form>
