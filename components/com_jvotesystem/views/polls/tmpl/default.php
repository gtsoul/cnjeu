<?php
/**
 * @package Component jVoteSystem for Joomla! 1.5-2.5
 * @projectsite www.joomess.de/projects/jvotesystem
 * @authors Johannes Meßmer, Andreas Fischer
 * @copyright (C) 2010 - 2012 Johannes Meßmer
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

//-- No direct access
defined('_JEXEC') or die('=;)');

$bold = "<strong>%s</strong>";
$category =& VBCategory::getInstance();
?>
<div id="jvotesystem" class="jvotesystem">
	<?php echo $this->toolbar->out("float:right;margin-top:8px;");?>
	<?php if($this->params->get("show_page_heading", 1)) {?>
		<?php if(!$this->menu) {?>
			<h1><?php echo JText::_("List_of_active_polls");?><?php if($this->filter["cid"] != 0) { echo ": ".@$category->getCategory($this->filter["cid"])->title; }?></h1>
		<?php } else {?>
			<h1><?php echo ($this->params->get("page_heading", "") != "") ? $this->params->get("page_heading", "") : $this->menu->title;?></h1>
	<?php } }?>
	<table class="list filters"><tbody>
		<?php if($this->params->get("showOrderBar", 1) || $this->params->get("showTimeBar", 1)) {?>
		<tr>
			<td>
				<?php if($this->params->get("showOrderBar", 1)) {?>
				<div style="float:left;">
					<a href="<?php echo $this->general->buildLink("list", null, "", array_merge($this->filter, array("order"=>"recent")));?>"><?php echo sprintf(($this->filter["order"] == "recent") ? $bold : "%s", JText::_("Most_Recent"));?></a>
					<strong> | </strong>
					<a href="<?php echo $this->general->buildLink("list", null, "", array_merge($this->filter, array("order"=>"most-voted")));?>"><?php echo sprintf(($this->filter["order"] == "most-voted") ? $bold : "%s", JText::_("Most_Voted"));?></a>
					<strong> | </strong>
					<a href="<?php echo $this->general->buildLink("list", null, "", array_merge($this->filter, array("order"=>"most-discussed")));?>"><?php echo sprintf(($this->filter["order"] == "most-discussed") ? $bold : "%s", JText::_("Most_Discussed"));?></a>
				</div>
				<?php } if($this->params->get("showTimeBar", 1)) {?>
				<div style="float:right;">
					<a href="<?php echo $this->general->buildLink("list", null, "", array_merge($this->filter, array("time"=>"today")));?>"><?php echo sprintf(($this->filter["time"] == "today") ? $bold : "%s", JText::_("Today"));?></a>
					<strong> | </strong>
					<a href="<?php echo $this->general->buildLink("list", null, "", array_merge($this->filter, array("time"=>"week")));?>"><?php echo sprintf(($this->filter["time"] == "week") ? $bold : "%s", JText::_("This_week"));?></a>
					<strong> | </strong>
					<a href="<?php echo $this->general->buildLink("list", null, "", array_merge($this->filter, array("time"=>"month")));?>"><?php echo sprintf(($this->filter["time"] == "month") ? $bold : "%s", JText::_("This_month"));?></a>
					<strong> | </strong>
					<a href="<?php echo $this->general->buildLink("list", null, "", array_merge($this->filter, array("time"=>"all-time")));?>"><?php echo sprintf(($this->filter["time"] == "all-time") ? $bold : "%s", JText::_("All_time"));?></a>
				</div>
				<?php }?>
			</td>
		</tr>
		<?php } if($this->params->get("showSearchField", 1) || $this->params->get("showCategoryDropdown", 1)) {?>
		<tr>
			<td><?php if($this->params->get("showSearchField", 1)) {?>
				<form action="<?php $u =& JURI::getInstance(); echo $u->toString();?>" method="post" name="jvsListFilter">
					<div style="float:left;">
						<input type="text" name="keyword" id="keyword" size="32" maxlength="250" value="<?php echo $this->filter["keyword"];?>" />
						<input type="submit" value="<?php echo JText::_("Find");?>" />
						<input type="button" value="<?php echo JText::_("Reset");?>" onclick="jVSQuery(this).parent().find('#keyword').val(''); jVSQuery(this).closest('form').submit();" />
					</div>
					<input type="hidden" name="order" value="<?php echo $this->filter["order"];?>" />
					<input type="hidden" name="time" value="<?php echo $this->filter["time"];?>" />
				</form>
				<?php } if($this->params->get("showCategoryDropdown", 1)) {?>
					<div style="float:right;">
						<?php echo JText::_("Category");?>: 
						<select name="cat" onchange="window.location.href = ('<?php echo $this->general->buildLink("list", null, "", array_merge($this->filter, array("cat"=>"replacecategorie")));?>').replace('replacecategorie', this.value);">
							<option value="all"><?php echo JText::_("All");?></option>
						<?php foreach($this->cats AS $cat) {?>
							<option<?php if($cat->id == $this->filter["cid"]) {?> selected="selected"<?php }?> value="<?php echo $cat->alias;?>"><?php for($i = 0; $i <= $cat->level; $i++) echo " - "; echo JText::_($cat->title);?></option>
						<?php }?>
						</select>
					</div>
				<?php }?>
			</td>
		</tr>
		<?php }?>
	</tbody></table>
	
	<?php echo $this->display("navi");?>	
	
	<?php echo $this->display($this->layout);?>
	
	<?php echo $this->display("navi");
		  echo '<p style="text-align: center;font-size: 11pt; font-style: italic; text-align: center;"><a href="http://joomess.de/projects/jvotesystem">jVoteSystem</a> developed and designed by <a href="http://www.joomess.de">www.joomess.de</a>.</p>';?>
		
</div>
