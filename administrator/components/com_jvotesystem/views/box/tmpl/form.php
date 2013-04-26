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
	} else if (form.title.value == ""){
		form.title.style.border = "2px solid red";
		form.title.focus();
	} else if (form.question.value == ""){
		form.question.style.border = "2px solid red";
		form.question.focus();
	} else if (form.object_group.value == ""){
		form.object_group.style.border = "2px solid red";
		form.object_group.focus();
	} else {
		submitform( task );
	}
}
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
	<table><tr><td>
   <fieldset class="adminform">
      <legend><?php echo JText::_('Details'); ?></legend>

      <table class="admintable">
      <tr>
         <td width="100" align="right" class="key">
            <label for="title">
               <?php echo JText::_('Title'); ?>:
            </label>
         </td>
         <td>
            <input class="text_area" type="text" name="title" id="title" size="32" maxlength="250" value="<?php echo $this->item->title;?>" />
         </td>
      </tr>
	  <tr>
         <td width="100" align="right" class="key">
            <label for="question">
               <?php echo JText::_('Question'); ?>:
            </label>
         </td>
         <td>
			<?php echo $this->general->getBBCodeToolbar("", 'question', false); ?>
            <textarea class="text_area" type="text" name="question" id="question" rows="6" cols="50"><?php echo $this->item->question;?></textarea>
         </td>
      </tr>
   </table>
   </fieldset></td>
   <td style="vertical-align: top;"><fieldset class="adminform">
      <legend><?php echo JText::_('Bereich_Expert'); ?></legend>

      <table class="admintable">
      <tr>
         <td width="100" align="right" class="key">
            <label for="object_group">
               Object_Group:
            </label>
         </td>
         <td>
            <input class="text_area" type="text" name="object_group" id="object_group" size="32" maxlength="250" value="<?php echo $this->item->object_group;?>" />
         </td>
      </tr>
	  <tr>
         <td width="100" align="right" class="key">
            <label for="object_id">
               Object_ID:
            </label>
         </td>
         <td>
            <input class="text_area" type="text" name="object_id" id="object_id" size="32" maxlength="250" value="<?php echo $this->item->object_id;?>" />
         </td>
      </tr>
   </table>
   </fieldset></td></tr></table>
  <table style="width: 100%;"><tr><td>
   <fieldset class="adminform" style="float:left;">
      <legend><?php echo JText::_('Einstellungen'); ?></legend>

      <table class="admintable">
      <tr>
         <td width="150" align="right" class="key">
            <label for="add_answer">
               <?php echo JText::_('ALLOW_TO_ADD_ANSWERs'); ?>:
            </label>
         </td>
         <td>
            <input <?php if($this->item->add_answer == 1) echo 'checked="checked"';?> type="radio" value="1" id="add_answer1" name="add_answer">
			<label for="add_answer1"><?php echo JText::_('JYES'); ?></label>
			<input <?php if($this->item->add_answer == 0) echo 'checked="checked"';?> type="radio" value="0" id="add_answer0" name="add_answer">
			<label for="add_answer0"><?php echo JText::_('JNO'); ?></label>
         </td>
		 <td width="150" align="right" class="key">
            <label for="start_time">
               <?php echo JText::_('Startdatum'); ?>:
            </label>
         </td>
         <td>
            <?php echo JHtml::_('calendar', $this->item->start_time, 'start_time', 'start_time', '%Y-%m-%d', 'style="vertical-align: top;
    width: 110px;"');?>
         </td>
      </tr>
	  <tr>
		<td width="150" align="right" class="key">
            <label for="add_comment">
               <?php echo JText::_('ALLOW_TO_ADD_COMMENTS'); ?>:
            </label>
         </td>
         <td>
            <input <?php if($this->item->add_comment == 1) echo 'checked="checked"';?> type="radio" value="1" id="add_comment1" name="add_comment">
			<label for="add_comment1"><?php echo JText::_('JYES'); ?></label>
			<input <?php if($this->item->add_comment == 0) echo 'checked="checked"';?> type="radio" value="0" id="add_comment0" name="add_comment">
			<label for="add_comment0"><?php echo JText::_('JNO'); ?></label>
         </td>
        <td width="150" align="right" class="key">
            <label for="end_time">
               <?php echo JText::_('Enddatum'); ?>:
            </label>
         </td>
         <td>
            <?php echo JHtml::_('calendar', $this->item->end_time, 'end_time', 'end_time', '%Y-%m-%d','style="vertical-align: top;
    width: 110px;"');?>
         </td>
      </tr>
   </table>
   </fieldset>
   <fieldset class="adminform" style="float:left;">
      <legend><?php echo JText::_('Votes').' - '.JText::_('Options'); ?></legend>

      <table class="admintable">
      <tr>
		<td width="150" align="right" class="key">
            <label for="allowed_votes">
               <?php echo JText::_('NUMBER_OF_POSSIBLE_VOTES'); ?>:
            </label>
         </td>
         <td>
            <input class="text_area" type="text" name="allowed_votes" id="allowed_votes" size="5" maxlength="3" value="<?php echo $this->item->allowed_votes;?>" />
         </td>
		 <td width="150" align="right" class="key">
            <label for="show_thankyou_message">
               <?php echo JText::_('SHOW_THANKYOU_MESSAGE_AFTER_VOTE'); ?>?
            </label>
         </td>
         <td>
            <input <?php if($this->item->show_thankyou_message == 1) echo 'checked="checked"';?> type="radio" value="1" id="show_thankyou_message1" name="show_thankyou_message">
			<label for="show_thankyou_message1"><?php echo JText::_('JYES'); ?></label>
			<input <?php if($this->item->show_thankyou_message == 0) echo 'checked="checked"';?> type="radio" value="0" id="show_thankyou_message0" name="show_thankyou_message">
			<label for="show_thankyou_message0"><?php echo JText::_('JNO'); ?></label>
         </td>
	  </tr>
	  <tr>
		<td width="150" align="right" class="key">
            <label for="max_votes_on_answer">
               <?php echo JText::_('MAX_VOTES_ON_ANSWER'); ?>:
            </label>
         </td>
         <td>
            <input class="text_area" type="text" name="max_votes_on_answer" id="max_votes_on_answer" size="5" maxlength="3" value="<?php echo $this->item->max_votes_on_answer;?>" />
         </td>
		 <td width="150" align="right" class="key">
            <label for="goto_chart">
               <?php echo JText::_('GOTO_CHART_AFTER_VOTE'); ?>?
            </label>
         </td>
         <td>
            <input <?php if($this->item->goto_chart == 1) echo 'checked="checked"';?> type="radio" value="1" id="goto_chart1" name="goto_chart">
			<label for="goto_chart1"><?php echo JText::_('JYES'); ?></label>
			<input <?php if($this->item->goto_chart == 0) echo 'checked="checked"';?> type="radio" value="0" id="goto_chart0" name="goto_chart">
			<label for="goto_chart0"><?php echo JText::_('JNO'); ?></label>
         </td>
	  </tr>
   </table>
   </fieldset>
  </td></tr></table><table style="width: 100%;"><tr><td>
	<fieldset class="adminform" style="float: left;">
      <legend><?php echo JText::_('LISTING'); ?></legend>
	  <table class="admintable">
      <tr>
         <td width="150" align="right" class="key">
            <label for="answers_orderby">
               <?php echo JText::_('answers_orderby'); ?>?
            </label>
         </td>
         <td>
            <select name="answers_orderby" id="answers_orderby">
				<?php 
				$orderbys = array(	array(	"name"	=>	JText::_("VOTES"),		"value"	=> "votes"),
									array(	"name"	=>	JText::_("ID"),			"value"	=> "id"),
									array(	"name"	=>	JText::_("Name"),		"value"	=> "name"),
									array(	"name"	=>	JText::_("Created"),	"value"	=> "created"));
				foreach($orderbys AS $orderby) { ?>
				<option <?php if($this->item->answers_orderby == $orderby["value"]) { ?> selected="true" <?php } ?> value="<?php echo $orderby["value"];?>">
					<?php echo $orderby["name"];?>
				</option>
				<?php } ?>
			</select>
         </td>
	  </tr>
	  <tr>
         <td width="150" align="right" class="key">
            <label for="answers_orderby_direction">
               <?php echo JText::_('answers_orderby_direction'); ?>?
            </label>
         </td>
         <td>
            <input <?php if($this->item->answers_orderby_direction == "DESC") echo 'checked="checked"';?> type="radio" value="DESC" id="answers_orderby_direction1" name="answers_orderby_direction">
			<label for="answers_orderby_direction1"><?php echo JText::_('DESC'); ?></label>
			<input <?php if($this->item->answers_orderby_direction == "ASC") echo 'checked="checked"';?> type="radio" value="ASC" id="answers_orderby_direction0" name="answers_orderby_direction">
			<label for="answers_orderby_direction0"><?php echo JText::_('ASC'); ?></label>
         </td>
	  </tr>
	  </table>
	</fieldset>
   <fieldset class="adminform" style="float: left;">
      <legend><?php echo JText::_('Ranking').' - '.JText::_('Options'); ?></legend>
	  <table class="admintable">
	  <tr>
	     <td width="150" align="right" class="key">
            <label for="activate_ranking">
               <?php echo JText::_('ACTIVATE_RANK'); ?>:
            </label>
         </td>
         <td>
            <input <?php if($this->item->activate_ranking == 1) echo 'checked="checked"';?> type="radio" value="1" id="activate_ranking1" name="activate_ranking">
			<label for="activate_ranking1"><?php echo JText::_('JYES'); ?></label>
			<input <?php if($this->item->activate_ranking == 0) echo 'checked="checked"';?> type="radio" value="0" id="activate_ranking0" name="activate_ranking">
			<label for="activate_ranking0"><?php echo JText::_('JNO'); ?></label>
         </td>
	  </tr>
      <tr>
         <td width="150" align="right" class="key">
            <label for="ranking_orderby">
               <?php echo JText::_('ranking_orderby'); ?>?
            </label>
         </td>
         <td>
            <select name="ranking_orderby" id="ranking_orderby">
				<?php 
				$orderbys = array(	array(	"name"	=>	JText::_("VOTES"),		"value"	=> "votes"),
									array(	"name"	=>	JText::_("ID"),			"value"	=> "id"),
									array(	"name"	=>	JText::_("Name"),		"value"	=> "name"),
									array(	"name"	=>	JText::_("Created"),	"value"	=> "created"));
				foreach($orderbys AS $orderby) { ?>
				<option <?php if($this->item->ranking_orderby == $orderby["value"]) { ?> selected="true" <?php } ?> value="<?php echo $orderby["value"];?>">
					<?php echo $orderby["name"];?>
				</option>
				<?php } ?>
			</select>
         </td>
	  </tr>
	  <tr>
         <td width="150" align="right" class="key">
            <label for="ranking_orderby_direction">
               <?php echo JText::_('ranking_orderby_direction'); ?>?
            </label>
         </td>
         <td>
            <input <?php if($this->item->ranking_orderby_direction == "DESC") echo 'checked="checked"';?> type="radio" value="DESC" id="ranking_orderby_direction1" name="ranking_orderby_direction">
			<label for="ranking_orderby_direction1"><?php echo JText::_('DESC'); ?></label>
			<input <?php if($this->item->ranking_orderby_direction == "ASC") echo 'checked="checked"';?> type="radio" value="ASC" id="ranking_orderby_direction0" name="ranking_orderby_direction">
			<label for="ranking_orderby_direction0"><?php echo JText::_('ASC'); ?></label>
         </td>
	  </tr>
	  </table>
	</fieldset>
   </td></tr></table>
   <fieldset class="adminform" style="width: 98%;">
      <legend><?php echo JText::_('Access'); ?></legend>
		<?php echo $this->lists->access;?>
		<?php /*<fieldset class="adminform" style="float: left;">
		  <legend><?php echo JText::_('Vote'); ?></legend>
		  <table class="admintable"><tr><td>
				<?php echo $this->lists->access;?>
			 </td></tr></table>
		</fieldset>
		<fieldset class="adminform" style="float: left;">
		  <legend><?php echo JText::_('add_answer'); ?></legend>
		  <table class="admintable"><tr><td>
				<?php echo $this->lists->add_answer;?>
			 </td></tr></table>
		</fieldset>
		<fieldset class="adminform" style="float: left;">
		  <legend><?php echo JText::_('add_comment'); ?></legend>
		  <table class="admintable"><tr><td>
				<?php echo $this->lists->add_comment;?>
			 </td></tr></table>
		</fieldset>
		<fieldset class="adminform" style="float: left;">
		  <legend><?php echo JText::_('admin'); ?></legend>
		  <table class="admintable"><tr><td>
				<?php echo $this->lists->admin_access;?>
			 </td></tr></table>
		</fieldset> */?>
   </fieldset>
   <?php /*if(!version_compare( JVERSION, '1.6.0', 'lt' )) { ?>
   <fieldset class="adminform" style="width: 100%;">
		<?php
		$actions = array();
			//Vote
			$action = array();
			$action["title"] = JText::_('Vote');
			$action["name"] = "access";
			$action["description"] = JText::_('Vote_description');
		$actions[] = $action;
			//add_answer
			$action = array();
			$action["title"] = JText::_('add_answer');
			$action["name"] = "add_answer";
			$action["description"] = JText::_('add_answer_description');
		$actions[] = $action;
			//add_comment
			$action = array();
			$action["title"] = JText::_('add_comment');
			$action["name"] = "add_comment";
			$action["description"] = JText::_('add_comment_description');
		$actions[] = $action;
			//Vote
			$action = array();
			$action["title"] = JText::_('admin');
			$action["name"] = "admin_access";
			$action["description"] = JText::_('admin_access_description');
		$actions[] = $action;
		
		$actions = JArrayHelper::toObject($actions);
		
		echo JHTML::_('rules.assetFormWidget', $actions);
		?>
	</fieldset>
	<?php } */?>
   <table style="width: 100%;"><tr><td>
    <fieldset class="adminform" style="float: left;">
      <legend><?php echo JText::_('E-Mail'); ?></legend>
	  <table class="admintable">
      <tr>
         <td width="150" align="right" class="key">
            <label for="send_mail_admin_answer">
               <?php echo JText::_('SENDING_EMAILS_ADMINS_NEW_ANSWERS'); ?>:
            </label>
         </td>
         <td>
            <input <?php if($this->item->send_mail_admin_answer == 1) echo 'checked="checked"';?> type="radio" value="1" id="send_mail_admin_answer1" name="send_mail_admin_answer">
			<label for="send_mail_admin_answer1"><?php echo JText::_('JYES'); ?></label>
			<input <?php if($this->item->send_mail_admin_answer == 0) echo 'checked="checked"';?> type="radio" value="0" id="send_mail_admin_answer0" name="send_mail_admin_answer">
			<label for="send_mail_admin_answer0"><?php echo JText::_('JNO'); ?></label>
         </td>
		 <td width="150" align="right" class="key">
            <label for="send_mail_user_answer_comments">
               <?php echo JText::_('SENDING_EMAILS_USER_NEW_COMMENTS'); ?>:
            </label>
         </td>
         <td>
            <input <?php if($this->item->send_mail_user_answer_comments == 1) echo 'checked="checked"';?> type="radio" value="1" id="send_mail_user_answer_comments1" name="send_mail_user_answer_comments">
			<label for="send_mail_user_answer_comments1"><?php echo JText::_('JYES'); ?></label>
			<input <?php if($this->item->send_mail_user_answer_comments == 0) echo 'checked="checked"';?> type="radio" value="0" id="send_mail_user_answer_comments0" name="send_mail_user_answer_comments">
			<label for="send_mail_user_answer_comments0"><?php echo JText::_('JNO'); ?></label>
         </td>
	  </tr>
	  <tr>
         <td width="150" align="right" class="key">
            <label for="send_mail_admin_comment">
               <?php echo JText::_('SENDING_EMAILS_ADMINS_NEW_COMMENTS'); ?>:
            </label>
         </td>
         <td>
            <input <?php if($this->item->send_mail_admin_comment == 1) echo 'checked="checked"';?> type="radio" value="1" id="send_mail_admin_comment1" name="send_mail_admin_comment">
			<label for="send_mail_admin_comment1"><?php echo JText::_('JYES'); ?></label>
			<input <?php if($this->item->send_mail_admin_comment == 0) echo 'checked="checked"';?> type="radio" value="0" id="send_mail_admin_comment0" name="send_mail_admin_comment">
			<label for="send_mail_admin_comment0"><?php echo JText::_('JNO'); ?></label>
         </td>
		 <td width="150" align="right" class="key">
            <label for="send_mail_user_comment_comments">
               <?php echo JText::_('SENDING_EMAILS_AUTHOR_NEW_COMMENTS'); ?>:
            </label>
         </td>
         <td>
            <input <?php if($this->item->send_mail_user_comment_comments == 1) echo 'checked="checked"';?> type="radio" value="1" id="send_mail_user_comment_comments1" name="send_mail_user_comment_comments">
			<label for="send_mail_user_comment_comments1"><?php echo JText::_('JYES'); ?></label>
			<input <?php if($this->item->send_mail_user_comment_comments == 0) echo 'checked="checked"';?> type="radio" value="0" id="send_mail_user_comment_comments0" name="send_mail_user_comment_comments">
			<label for="send_mail_user_comment_comments0"><?php echo JText::_('JNO'); ?></label>
         </td>
	  </tr>
	  </table>
	</fieldset>
	<fieldset class="adminform" style="float: left;">
      <legend><?php echo JText::_('SPAM_PROTECTION'); ?></legend>
	  <table class="admintable">
      <tr>
         <td width="150" align="right" class="key">
            <label for="activate_spam">
               <?php echo JText::_('ACTIVATE_SPAM_REPORT'); ?>?
            </label>
         </td>
         <td>
            <input <?php if($this->item->activate_spam == 1) echo 'checked="checked"';?> type="radio" value="1" id="activate_spam1" name="activate_spam">
			<label for="activate_spam1"><?php echo JText::_('JYES'); ?></label>
			<input <?php if($this->item->activate_spam == 0) echo 'checked="checked"';?> type="radio" value="0" id="activate_spam0" name="activate_spam">
			<label for="activate_spam0"><?php echo JText::_('JNO'); ?></label>
         </td>
		 <td style="width: 180px;" align="right" class="key">
            <label for="spam_count">
               <?php echo JText::_('LIMIT_OF_MAX_REPORTS'); ?>?
            </label>
         </td>
         <td>
           <input class="text_area" type="text" name="spam_count" id="spam_count" size="5" maxlength="3" value="<?php echo $this->item->spam_count;?>" />
         </td>
	  </tr>
	  <tr>
         <td width="150" align="right" class="key">
            <label for="spam_mail_admin_report">
               <?php echo JText::_('INFORM_ADMINS_REPORTS'); ?>?
            </label>
         </td>
         <td>
            <input <?php if($this->item->spam_mail_admin_report == 1) echo 'checked="checked"';?> type="radio" value="1" id="spam_mail_admin_report1" name="spam_mail_admin_report">
			<label for="spam_mail_admin_report1"><?php echo JText::_('JYES'); ?></label>
			<input <?php if($this->item->spam_mail_admin_report == 0) echo 'checked="checked"';?> type="radio" value="0" id="spam_mail_admin_report0" name="spam_mail_admin_report">
			<label for="spam_mail_admin_report0"><?php echo JText::_('JNO'); ?></label>
         </td>
		 <td width="150" align="right" class="key">
            <label for="spam_mail_admin_ban">
               <?php echo JText::_('INFORM_ADMINS_BLOCKS'); ?>?
            </label>
         </td>
         <td>
            <input <?php if($this->item->spam_mail_admin_ban == 1) echo 'checked="checked"';?> type="radio" value="1" id="spam_mail_admin_ban1" name="spam_mail_admin_ban">
			<label for="spam_mail_admin_ban1"><?php echo JText::_('JYES'); ?></label>
			<input <?php if($this->item->spam_mail_admin_ban == 0) echo 'checked="checked"';?> type="radio" value="0" id="spam_mail_admin_ban0" name="spam_mail_admin_ban">
			<label for="spam_mail_admin_ban0"><?php echo JText::_('JNO'); ?></label>
         </td>
	  </tr>
	  </table>
	</fieldset>
	</td></tr></table>
	<fieldset class="adminform" style="float:left;">
      <legend><?php echo JText::_('Result').' - '.JText::_('Options'); ?></legend>
		<table class="admintable">
			<tr><td class="key" style="float: left;">
				<label for="show_result">
				<?php echo JText::_('SHOW_RESULT_IF'); ?>...
				</label>
			</td></tr><tr><td>
				<input <?php if($this->item->show_result == "always") echo 'checked="checked"';?> type="radio" value="always" id="show_resultAlways" name="show_result">
				<label for="show_resultAlways"><?php echo JText::_('SHOW_RESULT_ALWAYS'); ?></label>
			</td></tr><tr><td>
				<input <?php if($this->item->show_result == "afterVote") echo 'checked="checked"';?> type="radio" value="afterVote" id="show_resultAfterVote" name="show_result">
				<label for="show_resultAfterVote"><?php echo JText::_('SHOW_RESULT_AFTER_VOTING'); ?></label>
			</td></tr><tr><td>
				<input <?php if($this->item->show_result == "afterDate") echo 'checked="checked"';?> type="radio" value="afterDate" id="show_resultAfterDate" name="show_result">
				<label for="show_resultAfterDate"><?php echo JText::_('SHOW_RESULT_AFTER_DATE'); ?>:</label>
				<?php echo JHtml::_('calendar', $this->item->show_result_after_date, 'show_result_after_date', 'show_result_after_date', '%Y-%m-%d','style="vertical-align: top;
    width: 110px;"');?>
			</td></tr><tr><td>
				<input <?php if($this->item->show_result == "never") echo 'checked="checked"';?> type="radio" value="never" id="show_resultNever" name="show_result">
				<label for="show_resultNever"><?php echo JText::_('SHOW_RESULT_NEVER'); ?></label>
			</td></tr>
		</table>
   </fieldset>
   <fieldset class="adminform" style="float:left;">
      <legend><?php echo JText::_('Display').' - '.JText::_('Options'); ?></legend>

      <table class="admintable">
      <tr>
	     <td width="150" align="right" class="key">
            <label for="show_author">
               <?php echo JText::_('show_author'); ?>
            </label>
         </td>
         <td>
            <input <?php if($this->item->show_author == 1) echo 'checked="checked"';?> type="radio" value="1" id="show_author1" name="show_author">
			<label for="show_author1"><?php echo JText::_('JYES'); ?></label>
			<input <?php if($this->item->show_author == 0) echo 'checked="checked"';?> type="radio" value="0" id="show_author0" name="show_author">
			<label for="show_author0"><?php echo JText::_('JNO'); ?></label>
         </td>
	  </tr>
	  </table>
   </fieldset>
	<fieldset class="adminform" style="width: 98%;">
      <legend><?php echo JText::_('Templates'); ?></legend>
	  
	  <table class="admintable">
	  <tr>
         <td width="150" align="right" class="key">
            <label for="template">
               <?php echo JText::_('Template'); ?>:
            </label>
         </td>
         <td>
            <select name="template" id="template">
			<?php foreach(VBTemplate::getTemplates() AS $template) { ?>
					<option <?php if($this->item->template == $template["id"]) { ?> selected="true" <?php } ?> value="<?php echo $template["id"];?>">
						<?php echo $template["name"];?>
					</option>
			<?php } ?>
			</select>
		 </td>
		 <td style="color: red; font-weight: bold;">
			DE: Die Templatesfunktion wird gerade noch entwickelt und befindet sich noch im BETA-Stadium. Momentan gibt es nur zwei Templates und eigene Templates können noch nicht erstellt werden. Fehler bitte im <a href="http://je.joomess.de/forum/jvotesystem.html" target="_blank">Forum</a> posten!<br>
			<p>EN: The templates feature is still being developed and is still in beta stage. Currently there are only two templates and custom templates can not be created. Please post occurring errors in the <a href="http://je.joomess.de/forum/jvotesystem.html" target="_blank">forum</a>!
		 </td>
	  </tr>
	  </table>
	</fieldset>
	
<div class="clr"></div>

<input type="hidden" name="option" value="com_jvotesystem" />
<input type="hidden" name="id" value="<?php echo $this->item->id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="boxen" />
</form>
<?php $this->general->getAdminFooter(); ?>