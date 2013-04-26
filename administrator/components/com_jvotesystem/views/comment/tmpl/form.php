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
	if (task == 'cancel') {
		submitform( task );
	} else if (form.answer_id.value == ""){
		form.answer_id.style.border = "2px solid red";
		form.answer_id.focus();
	} else if (form.comment.value == ""){
		form.comment.style.border = "2px solid red";
		form.comment.focus();
	} else {
		submitform( task );
	}
}
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
   <fieldset class="adminform">
      <legend><?php echo JText::_('Details'); ?></legend>

      <table class="admintable">
	  <tr>
         <td width="100" align="right" class="key">
            <label for="answer_id">
               <?php echo JText::_('Answer'); ?>:
            </label>
         </td>
         <td>
            <select name="answer_id" id="answer_id">
				<option <?php if($this->lists->id == '') { ?> selected="true" <?php } ?> value="">
					<?php echo JText::_('Select');?>
				</option>
				<?php foreach($this->lists->answers AS $answer) { ?>
				<option <?php if($answer->id == $this->lists->id) { ?> selected="true" <?php } ?> value="<?php echo $answer->id;?>">
					<?php echo $answer->answer;?>
				</option>
				<?php } ?>
			</select>
         </td>
      </tr>
      <tr>
         <td width="100" align="right" class="key">
            <label for="comment">
               <?php echo JText::_('Comment'); ?>:
            </label>
         </td>
         <td>
            <textarea class="text_area" type="text" name="comment" id="comment" rows="3" cols="50"><?php if(isset($this->item)) echo $this->item->comment;?></textarea>
         </td>
      </tr>
   </table>
   </fieldset>
   <fieldset class="adminform">
      <legend><?php echo JText::_('Statistik'); ?></legend>
		
   </fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_jvotesystem" />
<input type="hidden" name="id" value="<?php if(isset($this->item)) echo $this->item->id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="comments" />
<input type="hidden" name="aid" value="<?php echo $this->aid; ?>" />
<input type="hidden" name="bid" value="<?php echo $this->bid; ?>" />
<input type="hidden" name="object_group" value="<?php echo $this->section; ?>" />
</form>
<?php $this->general->getAdminFooter(); ?>