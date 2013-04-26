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
</div></td></tr></table>

<div id="editcell">
	<table class="adminlist">
	<thead>
		<tr>
			<th width="5">
				<?php echo JText::_( 'Category' ); ?>
			</th>
			<th width="5">
				<?php echo JText::_( 'ID' ); ?>
			</th>
			<th width="5">
				<?php echo JText::_( 'Poll' ); ?>
			</th>		
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
			</th>
			<th>
				<?php echo JText::_( 'Answer' ); ?>
			</th>
			<th width="1%">
				<?php echo JText::_( 'Autor' ); ?>
			</th>
			<th width="130">
				<?php echo JText::_( 'Created' ); ?>
			</th>
			<th width="130">
				<?php echo JText::_( 'First_Vote' ); ?>
			</th>
			<th width="130">
				<?php echo JText::_( 'Last_Vote' ); ?>
			</th>
			<th width="1%" nowrap="nowrap"><?php echo JText::_( 'PUBLISHED' ); ?></th>
			<th width="1%">
				<?php echo JText::_( 'Votes' ); ?>
			</th>
			<th width="100">
				<?php echo JText::_( 'of_the_last_2_weeks' ); ?>
			</th>
			<th width="1%">
				<?php echo JText::_( 'Comments' ); ?>
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
	<?php
	$k = 0;
	$this->charts->addchartjs('corechart');
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row = &$this->items[$i];
		$checked 	= JHTML::_('grid.id',   $i, $row->id );
		$link 		= JRoute::_( 'index.php?option=com_jvotesystem&controller=answers&bid='.$this->filter->bid.'&cid='.$this->filter->cid.'&view=answer&model=answer&task=edit&cid[]='.$row->id );
		
		$published 	= JHTML::_('grid.published', $row, $i );
		
		$catids = JArrayHelper::getColumn($this->filter->categories, "id");
		$pos = array_search($row->catid, $catids);
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $this->filter->categories[$pos]->title; ?>
			</td>
			<td>
				<?php echo $row->id; ?>
			</td>
			<td>
				<?php echo $row->poll; ?>
			</td>
			<td>
				<?php echo $checked; ?>
			</td>
			<td>
				<a href="<?php echo $link; ?>"><?php echo $row->answer; ?></a>
			</td>
			<td style="text-align:center;">
				<?php echo $row->autor_id; ?>
			</td>
			<td style="text-align:center;">
				<?php echo $row->created; ?>
			</td>
			<td style="text-align:center;">
				<?php echo $row->firstvote; ?>
			</td>
			<td style="text-align:center;">
				<?php echo $row->lastvote; ?>
			</td>
			<td style="text-align:center;">
				<?php echo $published; ?>
			</td>
			<td style="text-align:right;">
				<b><?php echo $row->votes; ?></b>
			</td>
			<td style="width: 200px;">
				<?php echo $this->charts->getBackendChart("answerVotesSmallgoogle", $row->id); ?>
			</td>
			<td style="text-align:right;">
				<a href="index.php?option=com_jvotesystem&view=comments&aid=<?php echo $row->id;?>&model=comments">
				<b><?php echo $row->comments; ?></b>
				<img src="components/com_jvotesystem/assets/images/icon-16-forward.png" />
				</a>
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
<input type="hidden" name="view" value="answers" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="answers" />
<input type="hidden" name="bid" value="<?php echo $this->filter->bid;?>" />
<input type="hidden" name="catid" value="<?php echo $this->filter->cid;?>" />
</form>

</div>
<?php $this->general->getAdminFooter(); ?>