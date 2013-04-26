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
		if (task == 'jeu.cancel' || document.formvalidator.isValid(document.id('jeu-form'))) {
			Joomla.submitform(task, document.getElementById('jeu-form'));
		}
	}
</script>
<!-- CNJ ajout pour autocompletion -->
<script type="text/javascript" src="../media/system/js/prototype.js"></script>
<script type="text/javascript" src="../media/system/js/effects.js"></script>
<script type="text/javascript" src="../media/system/js/controls.js"></script>  
<!-- !CNJ -->
<script src="components/com_cnj_jeux/com_cnj_jeux.js" type="text/javascript"></script>
<form action="<?php echo JRoute::_('index.php?option=com_cnj_jeux&layout=edit&id_jeu='.(int) $this->item->id_jeu); ?>" method="post" name="adminForm" id="jeu-form" class="form-validate">
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo empty($this->item->id_jeu) ? JText::_('COM_CNJ_JEUX_NEW_JEU') : JText::sprintf('COM_CNJ_JEUX_DETAILS', $this->item->id_jeu); ?></legend>
			<ul class="adminformlist">

				<li><?php echo $this->form->getLabel('publication'); ?>
				<?php echo $this->form->getInput('publication'); ?></li>
                                
				<li><?php echo $this->form->getLabel('type'); ?>
				<?php echo $this->form->getInput('type'); ?></li>
                                
				<li><?php echo $this->form->getLabel('titre'); ?>
				<?php echo $this->form->getInput('titre'); ?></li>
                                

				<li><?php echo $this->form->getLabel('traduction'); ?>
				<?php echo $this->form->getInput('traduction'); ?></li>
                                
 

                                <li><?php echo $this->form->getLabel('references'); ?>
                                <?php echo $this->form->getInput('references'); ?></li>
                                
				<li><?php echo $this->form->getLabel('reference_complete'); ?>
				<?php echo $this->form->getInput('reference_complete'); ?></li>
                                
				<li><?php echo $this->form->getLabel('numero_inventaire_editeur'); ?>
				<?php echo $this->form->getInput('numero_inventaire_editeur'); ?></li>
        
				<li><?php echo $this->form->getLabel('auteurs'); ?>
				<?php echo $this->form->getInput('auteurs'); ?></li>  
                                
				<li><?php echo $this->form->getLabel('date_parution_debut'); ?>
				<?php echo $this->form->getInput('date_parution_debut'); ?></li>
                                
				<li><?php echo $this->form->getLabel('date_parution_fin'); ?>
				<?php echo $this->form->getInput('date_parution_fin'); ?></li>
                                
				<li><?php echo $this->form->getLabel('date_parution_old'); ?>
				<?php echo $this->form->getInput('date_parution_old'); ?></li>
                                
				<li><?php echo $this->form->getLabel('informations_date'); ?>
				<?php echo $this->form->getInput('informations_date'); ?></li>
                                
				<li><?php echo $this->form->getLabel('pays_edition'); ?>
				<?php echo $this->form->getInput('pays_edition'); ?></li>
                                
				<li><?php echo $this->form->getLabel('disponibilite'); ?>
				<?php echo $this->form->getInput('disponibilite'); ?></li>

				<li><?php echo $this->form->getLabel('disponibilite_regle'); ?>
				<?php echo $this->form->getInput('disponibilite_regle'); ?></li>
                                
				<li><?php echo $this->form->getLabel('langue'); ?>
				<?php echo $this->form->getInput('langue'); ?></li>

				<li><?php echo $this->form->getLabel('lang_fr'); ?>
				<?php echo $this->form->getInput('lang_fr'); ?></li>
    
 				<li><?php echo $this->form->getLabel('lang_en'); ?>
				<?php echo $this->form->getInput('lang_en'); ?></li>

				<li><?php echo $this->form->getLabel('lang_es'); ?>
				<?php echo $this->form->getInput('lang_es'); ?></li>
  
				<li><?php echo $this->form->getLabel('lang_it'); ?>
				<?php echo $this->form->getInput('lang_it'); ?></li>
  
				<li><?php echo $this->form->getLabel('lang_de'); ?>
				<?php echo $this->form->getInput('lang_de'); ?></li>
    
				<li><?php echo $this->form->getLabel('lang_nl'); ?>
				<?php echo $this->form->getInput('lang_nl'); ?></li>
    
				<li><?php echo $this->form->getLabel('lang_au'); ?>
				<?php echo $this->form->getInput('lang_au'); ?></li>
                                   
				<li><?php echo $this->form->getLabel('transfert_nb_joueurs'); ?>
				<?php echo $this->form->getInput('transfert_nb_joueurs'); ?></li>
                                
				<li><?php echo $this->form->getLabel('age_indique'); ?>
				<?php echo $this->form->getInput('age_indique'); ?></li>
                                
				<li><?php echo $this->form->getLabel('temps_partie'); ?>
				<?php echo $this->form->getInput('temps_partie'); ?></li>
                                
				<li><?php echo $this->form->getLabel('type_materiel'); ?>
				<?php echo $this->form->getInput('type_materiel'); ?></li>
       
				<li><?php echo $this->form->getLabel('motcles'); ?>
				<?php echo $this->form->getInput('motcles'); ?></li>  
        
				<li><?php echo $this->form->getLabel('transfert_mot_cle'); ?>
				<?php echo $this->form->getInput('transfert_mot_cle'); ?></li>
                            
				<li><?php echo $this->form->getLabel('mecanismes'); ?>
				<?php echo $this->form->getInput('mecanismes'); ?></li>

				<li><?php echo $this->form->getLabel('mecanisme'); ?>
				<?php echo $this->form->getInput('mecanisme'); ?></li>
    
				<li><?php echo $this->form->getLabel('commentaire'); ?>
				<?php echo $this->form->getInput('commentaire'); ?></li>
                                
				<li><?php echo $this->form->getLabel('contenu_jeu'); ?>
				<?php echo $this->form->getInput('contenu_jeu'); ?></li>
                                
				<li><?php //echo $this->form->getLabel('grande_famille'); ?>
				<?php //echo $this->form->getInput('grande_famille'); ?></li>
                                
				<li><?php //echo $this->form->getLabel('gamme_jeu'); ?>
				<?php //echo $this->form->getInput('gamme_jeu'); ?></li>
                                
				<li><?php //echo $this->form->getLabel('sous_gamme'); ?>
				<?php //echo $this->form->getInput('sous_gamme'); ?></li>
                                
				<li><?php echo $this->form->getLabel('collection'); ?>
				<?php echo $this->form->getInput('collection'); ?></li>
                                
				<li><?php echo $this->form->getLabel('resume'); ?>
				<?php echo $this->form->getInput('resume'); ?></li>
                                
				<li><?php echo $this->form->getLabel('transfert_loc'); ?>
				<?php echo $this->form->getInput('transfert_loc'); ?></li>                               
   
				<li><?php echo $this->form->getLabel('autre_loc'); ?>
				<?php echo $this->form->getInput('autre_loc'); ?></li>                               
                             
				<li><?php echo $this->form->getLabel('format'); ?>
				<?php echo $this->form->getInput('format'); ?></li>
                        
				<li><?php echo $this->form->getLabel('matiere_technique'); ?>
				<?php echo $this->form->getInput('matiere_technique'); ?></li>
                                
				<li><?php echo $this->form->getLabel('prix'); ?>
				<?php echo $this->form->getInput('prix'); ?></li>

				<li><?php echo $this->form->getLabel('valeur_associee'); ?>
				<?php echo $this->form->getInput('valeur_associee'); ?></li>
                                
				<li><?php //echo $this->form->getLabel('livre_inventaire'); ?>
				<?php //echo $this->form->getInput('livre_inventaire'); ?></li>
                                
				<li><?php //echo $this->form->getLabel('numero_inventaire'); ?>
				<?php //echo $this->form->getInput('numero_inventaire'); ?></li>
                                
				<li><?php //echo $this->form->getLabel('isbn'); ?>
				<?php //echo $this->form->getInput('isbn'); ?></li>

				<li><?php echo $this->form->getLabel('id_jeu'); ?>
				<?php echo $this->form->getInput('id_jeu'); ?></li>

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
    
        <!--<div class="width-40 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::sprintf('COM_CNJ_JEUX_AUTEURS'); ?></legend>
			<ul class="adminformlist">
				<li><?php //echo $this->form->getLabel('auteurs'); ?>
				<?php echo $this->form->getInput('auteurs'); ?></li>                                
			</ul>
			<div class="clr"> </div>

		</fieldset>
	</div>
    
        <div class="width-40 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::sprintf('COM_CNJ_JEUX_REFERENCES'); ?></legend>
			<ul class="adminformlist">
				<li><?php //echo $this->form->getLabel('references'); ?>
				<?php echo $this->form->getInput('references'); ?></li>                                
			</ul>
			<div class="clr"> </div>

		</fieldset>
	</div>-->
    
        <div class="width-40 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::sprintf('COM_CNJ_JEUX_DOCUMENTS'); ?></legend>
			<ul class="adminformlist">
				<li><?php //echo $this->form->getLabel('documents'); ?>
				<?php echo $this->form->getInput('documents'); ?></li>                               
			</ul>
			<div class="clr"> </div>

		</fieldset>
	</div>
    
        <div class="width-40 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::sprintf('COM_CNJ_JEUX_DISTINCTIONS'); ?></legend>
			<ul class="adminformlist">
				<li><?php //echo $this->form->getLabel('distinctions'); ?>
				<?php echo $this->form->getInput('distinctions'); ?></li>                               
			</ul>
			<div class="clr"> </div>

		</fieldset>
	</div>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>

<div class="clr"></div>
</form>
