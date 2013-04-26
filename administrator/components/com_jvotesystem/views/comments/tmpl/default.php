<?php
/**
 * @package Component jVoteSystem for Joomla! 1.5 - 2.5
 * @projectsite www.joomess.de/projects/jvotesystem
 * @authors Johannes Meßmer, Andreas Fischer
 * @copyright (C) 2010 - 2012 Johannes Meßmer
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

//-- No direct access
defined('_JEXEC') or die('=;)');

?>
<form action="index.php" method="post" name="adminForm">
<table style="width:100%"><tr><td><div id="jvs"><div id="submenu-box" class="submenu-box">
        		<b><?php echo JText::_('Filter');?>:</b> 
				<i><?php echo JText::_('Category');?>:</i>
				<select onchange="document.adminForm.catid.value=this.value; submitform();" name="period">
					<option <?php if($this->filter->cid == '') { ?> selected="true" <?php } ?> value="">
						<?php echo JText::_('All');?>
					</option>
					<?php foreach($this->filter->categories AS $section) { ?>
					<option <?php if($section->id == $this->filter->cid) { ?> selected="true" <?php } ?> value="<?php echo $section->id;?>">
						<?php echo $section->title;?>
					</option>
					<?php } ?>
				</select> 
				<i><?php echo JText::_('Poll');?>:</i>
				<select onchange="document.adminForm.bid.value=this.value; submitform();" name="period">
					<option <?php if($this->filter->bid == '') { ?> selected="true" <?php } ?> value="">
						<?php echo JText::_('All');?>
					</option>
					<?php foreach($this->filter->boxen AS $box) { ?>
					<option <?php if($box->id == $this->filter->bid) { ?> selected="true" <?php } ?> value="<?php echo $box->id;?>">
						<?php echo $box->title;?>
					</option>
					<?php } ?>
				</select>
				<i><?php echo JText::_('Answer');?>:</i>
				<select onchange="document.adminForm.aid.value=this.value; submitform();" name="period">
					<option <?php if($this->filter->aid == '') { ?> selected="true" <?php } ?> value="">
						<?php echo JText::_('All');?>
					</option>
					<?php foreach($this->filter->answers AS $answer) { ?>
					<option <?php if($answer->id == $this->filter->aid) { ?> selected="true" <?php } ?> value="<?php echo $answer->id;?>">
						<?php echo $answer->answer;?>
					</option>
					<?php } ?>
				</select>
</div></td></tr></table>

<div id="editcell">
	<table class="adminlist">
	<thead>
		<tr>
			<th width="5">
				<?php echo JText::_( 'Section' ); ?>
			</th>
			<th width="5">
				<?php echo JText::_( 'Poll' ); ?>
			</th>
			<th width="5">
				<?php echo JText::_( 'ID' ); ?>
			</th>
			<th width="80">
				<?php echo JText::_( 'Answer' ); ?>
			</th>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
			</th>			
			<th>
				<?php echo JText::_( 'Kommentar' ); ?>
			</th>
			<th width="1%">
				<?php echo JText::_( 'Autor' ); ?>
			</th>
			<th width="130">
				<?php echo JText::_( 'Created' ); ?>
			</th>
			<th width="1%" nowrap="nowrap"><?php echo JText::_( 'PUBLISHED' ); ?></th>
		</tr>			
	</thead>
	<tfoot>
    <tr>
      <td colspan="10">
      	<?php echo $this->pagination->getListFooter(); ?>
      </td>
    </tr>
  </tfoot>
  <tbody>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row = &$this->items[$i];
		$checked 	= JHTML::_('grid.id',   $i, $row->id );
		$link 		= JRoute::_( 'index.php?option=com_jvotesystem&controller=comments&aid='.$this->filter->aid.'&view=comment&model=comment&task=edit&cid[]='. $row->id );
		
		$published 	= JHTML::_('grid.published', $row, $i );
		
		$catids = JArrayHelper::getColumn($this->filter->categories, "id");
		$pos = array_search($row->catid, $catids);
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $this->filter->categories[$pos]->title; ?>
			</td>
			<td>
				<?php echo $row->poll; ?>
			</td>
			<td>
				<?php echo $row->id; ?>
			</td>
			<td>
				<?php echo $row->answer; ?>
			</td>
			<td>
				<?php echo $checked; ?>
			</td>
			<td>
				<a href="<?php echo $link; ?>"><?php echo $row->comment; ?></a>
			</td>
			<td style="text-align:center;">
				<?php echo $row->autor_id; ?>
			</td>
			<td style="text-align:center;">
				<?php echo $row->created; ?>
			</td>
			<td style="text-align:center;">
				<?php echo $published; ?>
			</td>
		</tr>
		<?php
		$k = 1 - $k;
	}
	?>
	</tbody>
	</table>
</div>

<input type="hidden" name="option" value="com_jvotesystem" />
<input type="hidden" name="view" value="comments" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="comments" />
<input type="hidden" name="aid" value="<?php echo $this->filter->aid;?>" />
<input type="hidden" name="bid" value="<?php echo $this->filter->bid;?>" />
<input type="hidden" name="catid" value="<?php echo $this->filter->cid;?>" />
</form>
<?php $this->general->getAdminFooter(); ?>