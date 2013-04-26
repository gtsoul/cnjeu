<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_cnj_jeux
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

/*		$liste_qualites_auteur = array("Auteur",
				"Auteur JDR",
				"Collaboration",
				"Coloriste",
				"Création - Edition",
				"Créateur - Designer",
				"Dessinateur",
				"Directeur de publication",
				"Réalisation Graphique",
				"Historien du jeu",
				"Idée",
				"Illustration - graphisme",
				"Licence",
				"Mise en page",
				"Photographe",
				"Préfacier",
				"Recherche - Compilation",
				"Rédaction",
				"Scénariste",
				"Système de jeu",
				"Traducteur",
				"Textes"); 
*/



JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');



//var lines = text.split("\n");


//var lines = text.split("\n");




?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'reference.cancel' || document.formvalidator.isValid(document.id('reference-form'))) {
			Joomla.submitform(task, document.getElementById('reference-form'));
		}
  		
	}


</script>
<!-- CNJ ajout pour autocompletion -->
<script type="text/javascript" src="../media/system/js/prototype.js"></script>
<script type="text/javascript" src="../media/system/js/effects.js"></script>
<script type="text/javascript" src="../media/system/js/controls.js"></script>  
<!-- !CNJ -->
<script type="text/javascript"> 
	function zam()
	{
		alert("zoom");
	}
</script>


<script src="components/com_cnj_jeux/com_cnj_jeux.js" type="text/javascript"></script>



<form action="<?php 
if(array_key_exists('ofrom', $_GET )) 
{
    echo JRoute::_('index.php?option=com_cnj_jeux&layout=edit&ofrom=jeu&id_reference='.(int) $this->item->id_reference);
}
  else
{
    echo JRoute::_('index.php?option=com_cnj_jeux&layout=edit&id_reference='.(int) $this->item->id_reference);
}


 ?>" method="post" name="adminForm" id="reference-form" class="form-validate">
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo empty($this->item->id_reference) ? JText::_('COM_CNJ_JEUX_NEW_REFERENCE') : JText::sprintf('COM_CNJ_JEUX_DETAILS', $this->item->id_reference); ?></legend>
			<ul class="adminformlist">
				<li><?php echo $this->form->getLabel('nom'); ?>
				<?php 
if(empty($this->item->id_reference))
{
  if(array_key_exists('newreference', $_GET )) 
  {
    echo $this->form->getInput('nom',NULL,$_GET['newreference']); 
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
                                
				<li><?php echo $this->form->getLabel('alias'); ?>
				<?php echo $this->form->getInput('alias'); ?></li>
                                
				<li><?php echo $this->form->getLabel('historique'); ?>
				<?php echo $this->form->getInput('historique'); ?></li>
                                
				<li><?php echo $this->form->getLabel('commentaire'); ?>
				<?php echo $this->form->getInput('commentaire'); ?></li>
                                
				<li><?php echo $this->form->getLabel('pays'); ?>
				<?php echo $this->form->getInput('pays'); ?></li>
                                
				<li><?php echo $this->form->getLabel('site'); ?>
				<?php echo $this->form->getInput('site'); ?></li>
                                
				<li><?php echo $this->form->getLabel('collection'); ?>
				<?php echo $this->form->getInput('collection'); ?></li>
                                
				<li><?php echo $this->form->getLabel('adresse'); ?>
				<?php echo $this->form->getInput('adresse'); ?></li>

				<li><?php echo $this->form->getLabel('id_reference'); ?>
				<?php echo $this->form->getInput('id_reference'); ?></li>

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
