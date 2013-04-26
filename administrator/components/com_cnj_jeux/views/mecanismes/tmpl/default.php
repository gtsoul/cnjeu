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
<form action="<?php echo JRoute::_('index.php?option=com_cnj_jeux&view=mecanismes'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
                    <label class="filter-search-lbl" for="filter_titre"><?php echo JText::_('COM_CNJ_JEUX_FIELD_mecanisme'); ?>&nbsp;</label>
                    <td><input size="60" list="list_mecanisme"  type="text" name="filter_titre" id="filter_titre" value="<?php echo $this->escape($this->state->get('filter.titre')); ?>" title="<?php echo JText::_('COM_CNJ_JEUX_FIELD_mecanisme'); ?>" />

	<datalist id="list_mecanisme">
         <?php
                        $query_mecanisme = 'SELECT mecanisme FROM cnj_mecanisme order by mecanisme';
						$db = JFactory::getDBO();
                        $db->setQuery($query_mecanisme);
                        $distinct_mecanisme = $db->loadRowList();
                        foreach($distinct_mecanisme as $mecanisme) :
                		  echo '<option value="'.$mecanisme[0].'">';
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
					<?php echo JHtml::_('grid.sort',  'COM_CNJ_JEUX_HEADING_NAME', 'd.mecanisme', $listDirn, $listOrder); ?>
				</th>
				<th width="1%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'd.id_mecanisme', $listDirn, $listOrder); ?>
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
					<?php echo JHtml::_('grid.id', $i, $item->id_mecanisme); ?>
				</td>
				<td>
                                    <a href="<?php echo JRoute::_('index.php?option=com_cnj_jeux&task=mecanisme.edit&id_mecanisme='.(int) $item->id_mecanisme); ?>">
                                        <?php echo $this->escape($item->mecanisme); ?></a>
				</td>
				<td class="center">
                                    <?php echo $item->id_mecanisme; ?>
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
