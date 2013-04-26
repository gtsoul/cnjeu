<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_cnj_jeux
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

//$user		= JFactory::getUser();
//$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
//$canOrder	= $user->authorise('core.edit.state', 'com_cnj_jeux.category');
//$saveOrder	= $listOrder=='ordering';
$params		= (isset($this->state->params)) ? $this->state->params : new JObject();
?>
<form action="<?php echo JRoute::_('index.php?option=com_cnj_jeux&view=jeux'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
                    <label class="filter-search-lbl" for="filter_titre"><?php echo JText::_('COM_CNJ_JEUX_JSEARCH_TITRE_LABEL'); ?>&nbsp;</label>
                    <td><input size="60" list="list_titre"   type="text" name="filter_titre" id="filter_titre" value="<?php echo $this->escape($this->state->get('filter.titre')); ?>" title="<?php echo JText::_('COM_CNJ_JEUX_SEARCH_IN_TITLE'); ?>" />
                 <datalist  id="list_titre">
                <?php
                        $query_distinct_titre = 'SELECT titre FROM cnj_jeu order by titre';
				$db = JFactory::getDBO();
                        $db->setQuery($query_distinct_titre);
                        $distinct_titre = $db->loadRowList();			   
                        foreach($distinct_titre as $titre) :
                		  echo '<option value="'.$titre[0].'">';
                   		endforeach;
                ?>
                </datalist>
        	</td>

                   <label class="filter-search-lbl" for="filter_auteur"><?php echo JText::_('COM_CNJ_JEUX_JSEARCH_AUTEUR_LABEL'); ?>&nbsp;</label>
                   <td><input size="40" list="list_auteur"  type="text" name="filter_auteur" id="filter_auteur" value="<?php echo $this->escape($this->state->get('filter.auteur')); ?>" title="<?php echo JText::_('COM_CNJ_JEUX_SEARCH_IN_AUTEUR'); ?>" />

   		<datalist id="list_auteur">
                <?php
                        $query_distinct_auteur = 'SELECT nom FROM cnj_auteur order by nom';
				$db = JFactory::getDBO();
                        $db->setQuery($query_distinct_auteur);
                        $distinct_auteur = $db->loadRowList();
           			foreach($distinct_auteur as $auteur) :
                		  echo '<option value="'.$auteur[0].'">';
                   		endforeach;
                ?>
                </datalist>
		</td>

                    
                    <label class="filter-search-lbl" for="filter_reference"><?php echo JText::_('COM_CNJ_JEUX_JSEARCH_REFERENCE_LABEL'); ?>&nbsp;</label>
                    <td><input size="60" list="list_reference"  type="text" name="filter_reference" id="filter_reference" value="<?php echo $this->escape($this->state->get('filter.reference')); ?>" title="<?php echo JText::_('COM_CNJ_JEUX_SEARCH_IN_REFERENCE'); ?>" />
                   
        	<datalist id="list_reference">
                <?php
                        $query_distinct_reference = 'SELECT nom FROM cnj_reference order by nom';
						$db = JFactory::getDBO();
                        $db->setQuery($query_distinct_reference);
                        $distinct_reference = $db->loadRowList();
                        foreach($distinct_reference as $reference) :
                		  echo '<option value="'.$reference[0].'">';
                   		endforeach;
                ?>
                </datalist>
		</td>    


                    <label class="filter-search-lbl" for="filter_date_parution_debut"><?php echo JText::_('COM_CNJ_JEUX_JSEARCH_DATE_PARUTION_LABEL'); ?>&nbsp;</label>
                    <input type="text" name="filter_date_parution_debut" id="filter_date_parution_debut" value="<?php echo $this->escape($this->state->get('filter.date_parution_debut')); ?>" title="<?php echo JText::_('COM_CNJ_JEUX_SEARCH_IN_DATE_PARUTION_DEBUT'); ?>" />
                    
                    <label class="filter-search-lbl" for="filter_date_parution_fin"><?php echo JText::_('&agrave;'); ?>&nbsp;</label>
                    <input type="text" name="filter_date_parution_fin" id="filter_date_parution_fin" value="<?php echo $this->escape($this->state->get('filter.date_parution_fin')); ?>" title="<?php echo JText::_('COM_CNJ_JEUX_SEARCH_IN_DATE_PARUTION_FIN'); ?>" />
                    
                    <label class="filter-search-lbl" for="filter_motcle"><?php echo JText::_('COM_CNJ_JEUX_JSEARCH_MOT_CLE_LABEL'); ?>&nbsp;</label>
                    <td><input size= "60" list="list_motcle"  type="text" name="filter_motcle" id="filter_motcle" value="<?php echo $this->escape($this->state->get('filter.motcle')); ?>" title="<?php echo JText::_('COM_CNJ_JEUX_SEARCH_IN_MOT_CLE'); ?>" />

          	<datalist id="list_motcle">
                <?php
                        $db = JFactory::getDBO();
                        $db->setQuery('SELECT distinct motcle FROM cnj_motcle ');
                        $distinct_motcle = $db->loadRowList();
                        foreach($distinct_motcle as $motcle) :
                		  echo '<option value="'.$motcle[0].'">';
                   		endforeach;
               	   $db->setQuery('SELECT distinct mecanisme FROM cnj_mecanisme ');
                        $distinct_mecanisme = $db->loadRowList();
                        foreach($distinct_mecanisme as $mecanisme) :
                		  echo '<option value="'.$mecanisme[0].'">';
                   		endforeach;
                 ?>
                </datalist>
			</td>




			<td>
                    <label class="filter-search-lbl" for="filter_type"><?php echo JText::_('COM_CNJ_JEUX_JOPTION_SELECT_TYPE');?>&nbsp;</label>
                    <select name="filter_type" class="inputbox" onchange="this.form.submit()">
                            <option value=""></option>
                            <option value="jeu_de_role"<?php echo ($this->state->get('filter.type')=='jeu_de_role'?' selected="selected"':'');?>><?php echo JText::_('COM_CNJ_JEUX_JOPTION_SELECT_TYPE_VALUE1');?></option>
                            <option value="no_jeu_de_role"<?php echo ($this->state->get('filter.type')=='no_jeu_de_role'?' selected="selected"':'');?>><?php echo JText::_('COM_CNJ_JEUX_JOPTION_SELECT_TYPE_VALUE2');?></option>
                    </select>
                    </td>


			<td> 
                   <label class="filter-search-lbl" for="filter_publication"><?php echo JText::_('COM_CNJ_JEUX_JOPTION_SELECT_PUBLICATION');?>&nbsp;</label>
                    <select name="filter_publication" class="inputbox" onchange="this.form.submit()">
                            <option value=""></option>
                            <option value="publie"<?php echo ($this->state->get('filter.publication')=='publie'?' selected="selected"':'');?>><?php echo JText::_('COM_CNJ_JEUX_JOPTION_SELECT_PUBLICATION_VALUE1');?></option>
                            <option value="non_publie"<?php echo ($this->state->get('filter.publication')=='non_publie'?' selected="selected"':'');?>><?php echo JText::_('COM_CNJ_JEUX_JOPTION_SELECT_PUBLICATION_VALUE2');?></option>
                    </select>
		</td>


		<td> 
                   <label class="filter-search-lbl" for="filter_tri_1"><?php echo JText::_('COM_CNJ_JEUX_JOPTION_SELECT_TRI_1');?>&nbsp;</label>
                    <select name="filter_tri_1" class="inputbox" onchange="this.form.submit()">
                            <option value=""<?php echo ($this->state->get('filter.tri_1')==''?' selected="selected"':'');?>></option>
                            <option value="0"<?php echo ($this->state->get('filter.tri_1')=='0'?' selected="selected"':'');?>>0</option>
                            <option value="1"<?php echo ($this->state->get('filter.tri_1')=='1'?' selected="selected"':'');?>>1</option>
                    </select>
		</td>


		<td> 
                   <label class="filter-search-lbl" for="filter_tri_2"><?php echo JText::_('COM_CNJ_JEUX_JOPTION_SELECT_TRI_2');?>&nbsp;</label>
                    <select name="filter_tri_2" class="inputbox" onchange="this.form.submit()">
                            <option value=""<?php echo ($this->state->get('filter.tri_2')==''?' selected="selected"':'');?>></option>
                            <option value="0"<?php echo ($this->state->get('filter.tri_2')=='0'?' selected="selected"':'');?>>0</option>
                            <option value="1"<?php echo ($this->state->get('filter.tri_2')=='1'?' selected="selected"':'');?>>1</option>
                    </select>
		</td>

		<td> 
                   <label class="filter-search-lbl" for="filter_tri_3"><?php echo JText::_('COM_CNJ_JEUX_JOPTION_SELECT_TRI_3');?>&nbsp;</label>
                    <select name="filter_tri_3" class="inputbox" onchange="this.form.submit()">
                            <option value=""<?php echo ($this->state->get('filter.tri_3')==''?' selected="selected"':'');?>></option>
                            <option value="0"<?php echo ($this->state->get('filter.tri_3')=='0'?' selected="selected"':'');?>>0</option>
                            <option value="1"<?php echo ($this->state->get('filter.tri_3')=='1'?' selected="selected"':'');?>>1</option>
                    </select>
		</td>


		<td> 
                   <label class="filter-search-lbl" for="filter_tri_4"><?php echo JText::_('COM_CNJ_JEUX_JOPTION_SELECT_TRI_4');?>&nbsp;</label>
                    <select name="filter_tri_4" class="inputbox" onchange="this.form.submit()">
                            <option value=""<?php echo ($this->state->get('filter.tri_4')==''?' selected="selected"':'');?>></option>
                            <option value="0"<?php echo ($this->state->get('filter.tri_4')=='0'?' selected="selected"':'');?>>0</option>
                            <option value="1"<?php echo ($this->state->get('filter.tri_4')=='1'?' selected="selected"':'');?>>1</option>
                    </select>
		</td>



		</div>
		<div class="filter-search fltrt">
                    <button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
                    <button type="button" onclick="document.id('filter_titre').value='';document.id('filter_auteur').value='';document.id('filter_reference').value='';document.id('filter_date_parution_debut').value='';document.id('filter_date_parution_fin').value='';document.id('filter_motcle').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
	</fieldset>
	<div class="clr"> </div>

	<table class="adminlist">
		<thead>
			<tr>
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th>
					<?php echo JHtml::_('grid.sort',  'COM_CNJ_JEUX_HEADING_NAME', 'j.titre', $listDirn, $listOrder); ?>
				</th>
				<th width="15%">
					<?php echo JText::_('COM_CNJ_JEUX_HEADING_AUTEURS'); ?>
				</th>
				<th width="15%">
					<?php echo JText::_('COM_CNJ_JEUX_HEADING_REFERENCES'); ?>
				</th>
				<th width="15%">
					<?php echo JText::_('COM_CNJ_JEUX_HEADING_DOCUMENTS'); ?>
				</th>
				<th width="15%">
					<?php echo JText::_('COM_CNJ_JEUX_HEADING_DISTINCTIONS'); ?>
				</th>
				<th width="5%">
					<?php echo JText::_('COM_CNJ_JEUX_HEADING_DATE_PARUTION'); ?>
				</th>
				<th width="5%">
					<?php echo JText::_('COM_CNJ_JEUX_HEADING_INFOS_DATE'); ?>
				</th>
				<th width="5%">
					<?php echo JHtml::_('grid.sort', 'COM_CNJ_JEUX_HEADING_VERSION', 'j.pays_edition', $listDirn, $listOrder); ?>
				</th>
				<th width="1%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'j.id_jeu', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="13">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>

		<?php foreach ($this->items as $i => $item) :
			//$ordering	= ($listOrder == 'ordering');
			//$canCreate	= $user->authorise('core.create',		'com_cnj_jeux.category.'.$item->catid);
			//$canEdit	= $user->authorise('core.edit',			'com_cnj_jeux.category.'.$item->catid);
			//$canCheckin	= $user->authorise('core.manage',		'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
			//$canChange	= $user->authorise('core.edit.state',	'com_cnj_jeux.category.'.$item->catid) && $canCheckin;
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->id_jeu); ?>
				</td>
				<td>
                                    <a href="<?php echo JRoute::_('index.php?option=com_cnj_jeux&task=jeu.edit&id_jeu='.(int) $item->id_jeu); ?>">
                                        <?php echo $this->escape($item->titre); ?></a>
				</td>
				<td>
                                    <?php if(count($item->auteurs)>0) {
                                        $txt = ''; $sep = '<br />';
                                        foreach($item->auteurs as $auteur) {
                                            $txt .= '&bull; ' . $this->escape($auteur->nom) . $sep;
                                        }
                                        echo $txt;
                                    } ?>
				</td>


<?php /*				<td>
                                    <?php 
                                    if(count($item->motcles)>0) {
                                        $txt = ''; $sep = '<br />';
                                        foreach($item->motcles as $dist) {
                                            $txt .= '&bull; ' . $this->escape($dist->motcle) . $sep;
                                        }
                                        echo $txt;
                                    }
                                    if(count($item->mecanismes)>0) {
                                        $txt = ''; $sep = '<br />';
                                        foreach($item->mecanismes as $dist) {
                                            $txt .= '&bull; ' . $this->escape($dist->mecanisme) . $sep;
                                        }
                                        echo $txt;
                                    } 
 ?>
				</td>
	*/ ?>

				<td>
                                    <?php 
                                    if(count($item->references)>0) {
                                        $txt = ''; $sep = '<br />';
                                        foreach($item->references as $ref) {
                                            $txt .= '&bull; ' . $this->escape($ref->nom) . $sep;
                                        }
                                        echo $txt;
                                    } ?>
				</td>
				<td>
                                    <?php 
                                    if(count($item->documents)>0) {
                                        $txt = ''; $sep = '<br />';
                                        foreach($item->documents as $doc) {
                                            $txt .= '&bull; ' . $this->escape($doc->nom) . $sep;
                                        }
                                        echo $txt;
                                    } ?>
				</td>
				<td>
                                    <?php 
                                    if(count($item->distinctions)>0) {
                                        $txt = ''; $sep = '<br />';
                                        foreach($item->distinctions as $dist) {
                                            $txt .= '&bull; ' . $this->escape($dist->nom) . $sep;
                                        }
                                        echo $txt;
                                    } ?>
				</td>
				<td class="center">
                                    <?php echo $this->escape($item->date_parution_debut); ?>
				</td>
				<td class="center">
                                    <?php echo $this->escape($item->informations_date); ?>
				</td>
				<td class="center">
                                    <?php echo $this->escape($item->pays_edition); ?>
				</td>
				<td class="center">
                                    <?php echo $item
->id_jeu; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
