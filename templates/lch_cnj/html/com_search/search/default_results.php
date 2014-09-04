<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_search
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>

<dl class="search-results<?php echo $this->pageclass_sfx; ?> item-page">
<?php foreach($this->results as $result) : ?>
	<dt class="result-title">
		<h3>
			<?php if ($result->section) : ?>
				<dd class="result-category" style="float:right;">
					<span class="small<?php echo $this->pageclass_sfx; ?> titre-abel">
						(<?php echo $this->escape($result->section); ?>)
					</span>
				</dd>
			<?php endif; ?>		
			<?php if ($result->href) :?>
				<?php echo $this->pagination->limitstart + $result->count.'. ';?>			
				<a href="<?php echo JRoute::_($result->href); ?>"<?php if ($result->browsernav == 1) :?> target="_blank"<?php endif;?>>
					<?php echo $this->escape($result->title);?>
				</a>
			<?php else:?>
				<?php echo $this->pagination->limitstart + $result->count.'. ';?>			
				<?php echo $this->escape($result->title);?>
			<?php endif; ?>
		</h3>
	</dt>

	<dd class="result-text">
		<?php echo $result->text; ?>
	</dd>
	<?php if ($this->params->get('show_date')) : ?>
		<dd class="result-created<?php echo $this->pageclass_sfx; ?>">
			<?php echo JText::sprintf('JGLOBAL_CREATED_DATE_ON', $result->created); ?>
		</dd>
	<?php endif; ?>
<?php endforeach; ?>
</dl>

<div class="pagination">
	<?php echo $this->pagination->getPagesLinks(); ?>
</div>
