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
	} else if (form.box_id.value == ""){
		form.box_id.style.border = "2px solid red";
		form.box_id.focus();
	} else if (form.answer.value == ""){
		form.answer.style.border = "2px solid red";
		form.answer.focus();
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
            <label for="box_id">
               <?php echo JText::_('Poll'); ?>:
            </label>
         </td>
         <td>
            <select name="box_id" id="box_id">
				<option <?php if($this->lists->id == '') { ?> selected="true" <?php } ?> value="">
					<?php echo JText::_('Select');?>
				</option>
				<?php foreach($this->lists->polls AS $box) { ?>
				<option <?php if($box->id == $this->lists->id) { ?> selected="true" <?php } ?> value="<?php echo $box->id;?>">
					<?php echo $box->title;?>
				</option>
				<?php } ?>
			</select>
         </td>
      </tr>
      <tr>
         <td width="100" align="right" class="key">
            <label for="answer">
               <?php echo JText::_('Answer'); ?>:
            </label>
         </td>
         <td>
            <textarea class="text_area" type="text" name="answer" id="answer" rows="3" cols="50"><?php if(isset($this->item)) echo $this->item->answer;?></textarea>
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
<input type="hidden" name="controller" value="answers" />
<input type="hidden" name="bid" value="<?php echo $this->bid; ?>" />
<input type="hidden" name="object_group" value="<?php echo $this->section; ?>" />
</form>
<?php $this->general->getAdminFooter(); ?>