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
<script type="text/javascript">
<?php if(version_compare( JVERSION, '1.6.0', 'lt' )) { ?>
function submitbutton(task) {
<?php } else { ?>
Joomla.submitbutton = function(task) {
<?php } ?>
	var form = document.adminForm;
	if (task == "add") { 
		loadAssistant(this, "<?php echo JUri::root(true);?>", "poll");
	} else if(task == "edit") {
		var count = 0;
		jVSQuery("input[type=checkbox][name^='cid']").each(
			function() {
				if( jVSQuery(this).attr('checked')){
					if(count != 0) return ;
					count++;
					loadAssistant(this, "<?php echo JUri::root(true);?>", "poll", "&id=" + jVSQuery(this).val());
				}
			}
		);
	} else {
		submitform( task );
	}
} 
</script>
							
<form action="index.php" method="post" name="adminForm">
<div id="editcell">
	<table class="adminlist">
	<thead>
		<tr>
			<th width="200">
				<?php echo JText::_( 'Votes' ).' '.JText::_( 'of_the_last_2_weeks' ); ?>
			</th>
			<th width="5">
				<?php echo JText::_( 'ID' ); ?>
			</th>
			<th width="5">
				<?php echo JText::_( 'Category' ); ?>
			</th>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
			</th>			
			<th>
				<?php echo JText::_( 'Title' ); ?>
			</th>
			<th>
				<?php echo JText::_( 'Question' ); ?>
			</th>
			<th width="1%" nowrap="nowrap"><?php echo JText::_( 'PUBLISHED' ); ?></th>
			<th width="1%"></th>
			<th width="1%"><?php echo JHTML::_('grid.order', $this->items, 'filesave.png', 'saveorderboxen' ); ?></th>
			<th width="1%">
				<?php echo JText::_( 'Answers' ); ?>
			</th>
			<th width="1%">
				<?php echo JText::_( 'Votes' ); ?>
			</th>
			<th width="1%">
				<?php echo JText::_( 'Comments' ); ?>
			</th>
		</tr>			
	</thead>
	<tfoot>
    <tr>
      <td colspan="12">
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
		$link 		= JUri::root(true) . '/components/com_jvotesystem/assistant/index.php?interface=administrator&view=poll&id='. $row->id ;
		
		$params = array();
		$params["id"] = $row->id;
		$onclick 	= "loadAssistant(this, '".JUri::root(true)."', 'poll', '&id=".$row->id."'); return false;";
		
		$published 	= JHTML::_('grid.published', $row, $i );
		
		$catlink 	= "index.php?option=com_jvotesystem&view=category&id=".$row->catid;
		?>
		<tr class="<?php echo "row$k"; ?>" style="font-size: 11pt;">
			<td style="width: 200px;">
				<?php echo $this->charts->getBackendChart("boxVotesSmallgoogle", $row->id); ?>
			</td>
			<td>
				<?php echo $row->id; ?>
			</td>
			<td>
				<a href="<?php echo $catlink; ?>">
					<?php echo $row->cattitle; ?>
				</a>
			</td>
			<td>
				<?php echo $checked; ?>
			</td>
			<td>
				<a onclick="<?php echo $onclick; ?>" href="<?php echo $link; ?>"><?php echo $row->title; ?></a>
			</td>
			<td>
				<a onclick="<?php echo $onclick; ?>" href="<?php echo $link; ?>"><?php echo $row->question; ?></a>
			</td>
			<td style="text-align:center;">
				<?php echo $published; ?>
			</td>
			<td class="order" colspan="2">
				<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="text_area" style="text-align: center" />
			</td>
			<td style="background-image: url('components/com_jvotesystem/assets/images/icon-32-forward.png'); background-repeat: no-repeat; background-position: right center; text-align: left;cursor:pointer;" onclick="location='index.php?option=com_jvotesystem&view=answers&bid=<?php echo $row->id;?>&model=answers'">
				<a href="index.php?option=com_jvotesystem&view=answers&bid=<?php echo $row->id;?>&model=answers" style="text-decoration: none ! important;">
				<b style="font-size: 14pt;"><?php echo $row->answers; ?></b>
			</td>
			<td style="text-align:right;">
				<b><?php echo $row->votes; ?></b>
			</td>
			<td style="text-align:right;">
				<b><?php echo $row->comments; ?></b>
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
<input type="hidden" name="view" value="boxen" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="boxen" />
</form>
<?php $this->general->getAdminFooter(); ?>