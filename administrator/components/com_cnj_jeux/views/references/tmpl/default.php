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
<form action="<?php echo JRoute::_('index.php?option=com_cnj_jeux&view=references'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
                    <label class="filter-search-lbl" for="filter_titre"><?php echo JText::_('COM_CNJ_JEUX_JSEARCH_REFERENCE_LABEL'); ?>&nbsp;</label>
                   <td> <input size= "60"  list="list_reference" type="text" name="filter_titre" id="filter_titre" value="<?php echo $this->escape($this->state->get('filter.titre')); ?>" title="<?php echo JText::_('COM_CNJ_JEUX_JSEARCH_REFERENCE_LABEL'); ?>" />
     

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





               <button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
                    <button type="button" onclick="document.id('filter_titre').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
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
					<?php echo JHtml::_('grid.sort',  'COM_CNJ_JEUX_HEADING_NAME', 'r.nom', $listDirn, $listOrder); ?>
				</th>
				<th width="10%">
					<?php echo JHtml::_('grid.sort', 'COM_CNJ_JEUX_HEADING_ALIAS', 'r.alias', $listDirn, $listOrder); ?>
				</th>
				<th width="10%">
					<?php echo JHtml::_('grid.sort', 'COM_CNJ_JEUX_HEADING_PAYS', 'r.pays', $listDirn, $listOrder); ?>
				</th>
				<th width="10%">
					<?php echo JHtml::_('grid.sort', 'COM_CNJ_JEUX_HEADING_ADRESSE', 'r.adresse', $listDirn, $listOrder); ?>
				</th>
				<th width="15%">
					<?php echo JText::_('COM_CNJ_JEUX_HEADING_DOCUMENTS'); ?>
				</th>
				<th width="1%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'r.id_reference', $listDirn, $listOrder); ?>
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
					<?php echo JHtml::_('grid.id', $i, $item->id_reference); ?>
				</td>
				<td>
                                    <a href="<?php echo JRoute::_('index.php?option=com_cnj_jeux&task=reference.edit&id_reference='.(int) $item->id_reference); ?>">
                                        <?php echo $this->escape($item->nom); ?></a>
				</td>
				<td class="center">
                                    <?php echo $this->escape($item->alias); ?>
				</td>
				<td class="center">
                                    <?php echo $this->escape($item->pays); ?>
				</td>
				<td class="center">
                                    <?php echo $this->escape($item->adresse); ?>
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
				<td class="center">
                                    <?php echo $item->id_reference; ?>
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
