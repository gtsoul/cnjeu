<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
 
// load tooltip behavior
JHtml::_('behavior.tooltip');

$model = $this->getModel();
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$ordering 	= ($listOrder == 'ordering');
$saveOrder 	= ($listOrder == 'ordering' && $listDirn == 'asc');
?>
<form action="index.php?option=com_alfcontact&amp;view=alfcontacts" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="Searcht" />

			<button type="submit" class="btn"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select fltrt">

			<select name="filter_published" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions', array('archived' => false)), 'value', 'text', $this->state->get('filter.published'), true);?>
			</select>

            <select name="filter_access" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_ACCESS');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->state->get('filter.access'));?>
			</select>
			
		</div>
	</fieldset>
        
		<table class="adminlist">
            <thead>
				<tr>        
					<th width="1%">
						<input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
					</th>  
					<th width="12%" align="left"><?php echo JHtml::_('grid.sort', 'COM_ALFCONTACT_CONTACT_NAME', 'name', $listDirn, $listOrder) ?></th>
					<th width="10%" align="left"><?php echo JText::_('COM_ALFCONTACT_CONTACT_EMAIL') ?></th>
					<th width="9%" align="left"><?php echo JText::_('COM_ALFCONTACT_CONTACT_PREFIX') ?></th>
					<th width="9%" align="left"><?php echo JText::_('COM_ALFCONTACT_CONTACT_EXTRA') ?></th>
					<th width="9%" align="left"><?php echo JText::_('COM_ALFCONTACT_CONTACT_EXTRA') ?></th>
					<th align="left"><?php echo JText::_('COM_ALFCONTACT_CONTACT_DEFAULT_SUBJECT') ?></th>
					<th width="5%" align="center"><?php echo JHtml::_('grid.sort', 'JSTATUS', 'published', $listDirn, $listOrder) ?></th>
					<th width="10%">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ORDERING', 'ordering', $listDirn, $listOrder); ?>
					<?php if ($saveOrder) :?>
						<?php echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'alfcontacts.saveorder'); ?>
					<?php endif; ?>
					</th>
					<th width="10%" align="center"><?php echo JHtml::_('grid.sort', 'COM_ALFCONTACT_CONTACT_ACCESS', 'access', $listDirn, $listOrder) ?></th>
					<th width="1%" align="center"><?php echo JText::_('COM_ALFCONTACT_CONTACT_ID') ?></th>
				</tr>	
			</thead>
			<tfoot>
				<tr>
					<td colspan="11"><?php echo $this->pagination->getListFooter(); ?></td>
				</tr>
			</tfoot>
			<tbody><?php 
				foreach($this->items as $i => $item): ?>
					<tr class="row<?php echo $i % 2 ?>">
						<td><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
						<td><a href="<?php echo $item->url; ?>"><?php echo $item->name; ?></a></td>
						<td><?php echo $item->email; ?></a></td>
						<td><?php echo $item->prefix; ?></td>
						<td><?php echo $item->extra; ?></td>
						<td><?php echo $item->extra2; ?></td>
						<td><?php echo $item->defsubject; ?></td>
						<td align="center"><?php echo JHtml::_('jgrid.published', $item->published, $i, 'alfcontacts.', true, 'cb'); ?></td>
						<td class="order">
							<?php if ($saveOrder) : ?>
								<span><?php echo $this->pagination->orderUpIcon($i, true, 'alfcontacts.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
								<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'alfcontacts.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
							<?php endif; ?>
							<?php $disabled = $saveOrder ?  '' : 'disabled="disabled"'; ?>
							<input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" <?php echo $disabled ?> class="text-area-order" />
						</td>
						<td class="center"><?php echo $this->escape($item->access_level); ?></td>
						<td><?php echo $item->id; ?></td>
					</tr>
				<?php endforeach;?>
			</tbody>
			
        </table>

        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
        <?php echo JHtml::_('form.token'); ?>

</form>
