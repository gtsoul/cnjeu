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
//$saveOrder	= $listOrder=='d.ordre';
$params		= (isset($this->state->params)) ? $this->state->params : new JObject();
?>
<form action="<?php echo JRoute::_('index.php?option=com_cnj_jeux&view=documents'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
                    <label class="filter-search-lbl" for="filter_titre"><?php echo JText::_('COM_CNJ_JEUX_FIELD_BASENAME_LABEL'); ?>&nbsp;</label>
                   <td> <input list = "list_document" size="80" type="text" name="filter_titre" id="filter_titre" value="<?php echo $this->escape($this->state->get('filter.titre')); ?>" title="<?php echo JText::_('COM_CNJ_JEUX_FIELD_BASENAME_LABEL'); ?>" />
  	<datalist id="list_document">
                <?php
                        $query_distinct_reference = 'SELECT nom FROM cnj_document order by nom';
						$db = JFactory::getDBO();
                        $db->setQuery($query_distinct_reference);
                        $distinct_reference = $db->loadRowList();
                        foreach($distinct_reference as $reference) :
                		  echo '<option value="'.$reference[0].'">';
                   		endforeach;
                ?>
                </datalist>
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
					<?php echo JHtml::_('grid.sort',  'COM_CNJ_JEUX_HEADING_NAME', 'd.nom', $listDirn, $listOrder); ?>
				</th>
				<th width="10%">
					<?php echo JHtml::_('grid.sort', 'COM_CNJ_JEUX_HEADING_TYPE', 'd.type', $listDirn, $listOrder); ?>
				</th>
				<th width="10%">
					<?php echo JHtml::_('grid.sort', 'COM_CNJ_JEUX_HEADING_PATH_HD', 'd.path_hd', $listDirn, $listOrder); ?>
				</th>
				<!--<th width="10%">
					<?php //echo JHtml::_('grid.sort',  'JGRID_HEADING_ORDERING', 'd.ordre', $listDirn, $listOrder); ?>
					<?php //if ($saveOrder) :?>
						<?php //echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'documents.saveorder'); ?>
					<?php //endif; ?>
				</th>-->
				<th width="1%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_OLD_ID', 'd.old_id_document', $listDirn, $listOrder); ?>
				</th>
				<th width="1%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'd.id', $listDirn, $listOrder); ?>
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
			/*$ordering	= ($listOrder == 'd.ordre');
			$canCreate	= $user->authorise('core.create',		'com_cnj_jeux.category.'.$item->catid);
			$canEdit	= $user->authorise('core.edit',			'com_cnj_jeux.category.'.$item->catid);
			$canCheckin	= $user->authorise('core.manage',		'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
			$canChange	= $user->authorise('core.edit.state',	'com_cnj_jeux.category.'.$item->catid) && $canCheckin;*/
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				<td>
                                    <a href="<?php echo JRoute::_('index.php?option=com_cnj_jeux&task=document.edit&id='.(int) $item->id); ?>">
                                        <?php echo $this->escape($item->nom); ?></a>
				</td>
				<td class="center">
                                    <?php echo $this->escape($item->type); ?>
				</td>
				<td class="center">
                                    <?php echo $this->escape($item->path_hd); ?>
				</td>
				<!--<td class="order">
					<?php //if ($canChange) : ?>
						<?php //if ($saveOrder) :?>
							<?php //if ($listDirn == 'asc') : ?>
								<span><?php //echo $this->pagination->orderUpIcon($i, ($item->old_id_document == @$this->items[$i-1]->old_id_document), 'documents.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
								<span><?php //echo $this->pagination->orderDownIcon($i, $this->pagination->total, ($item->old_id_document == @$this->items[$i+1]->old_id_document), 'documents.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
							<?php //elseif ($listDirn == 'desc') : ?>
								<span><?php //echo $this->pagination->orderUpIcon($i, ($item->old_id_document == @$this->items[$i-1]->old_id_document), 'documents.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
								<span><?php //echo $this->pagination->orderDownIcon($i, $this->pagination->total, ($item->old_id_document == @$this->items[$i+1]->old_id_document), 'documents.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
							<?php //endif; ?>
						<?php //endif; ?>
						<?php //$disabled = $saveOrder ?  '' : 'disabled="disabled"'; ?>
						<input type="text" name="order[]" size="5" value="<?php //echo $item->ordre;?>" <?php //echo $disabled ?> class="text-area-order" />
					<?php //else : ?>
						<?php //echo $item->ordre; ?>
					<?php //endif; ?>
				</td>-->
				<td class="center">
                                    <?php echo $item->old_id_document; ?>
				</td>
				<td class="center">
                                    <?php echo $item->id; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php //Load the batch processing form. ?>
	<?php //echo $this->loadTemplate('batch'); ?>

	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
