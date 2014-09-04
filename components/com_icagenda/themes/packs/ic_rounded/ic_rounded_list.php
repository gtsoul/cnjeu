<?php
/**
 *------------------------------------------------------------------------------
 *  iCagenda v3 by Jooml!C - Events Management Extension for Joomla! 2.5 / 3.x
 *------------------------------------------------------------------------------
 * @package     com_icagenda
 * @copyright   Copyright (c)2012-2013 Cyril Rezé, Jooml!C - All rights reserved
 *
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Cyril Rezé (Lyr!C)
 * @link        http://www.joomlic.com
 *
 * @themepack	ic_rounded
 * @template	events_list
 * @version 	3.2.7 2013-11-23
 * @since       1.0
 *------------------------------------------------------------------------------
*/

// No direct access to this file
defined('_JEXEC') or die();?>

<!--
 *
 * iCagenda by Jooml!C
 * ic_rounded Official Theme Pack
 *
 * @template	events_list
 * @version 	3.2.3 2013-10-20
 *
-->

<?php // List of Events Template ?>

	<?php // List of Events ?>
	<div class="items">

	<?php // Display for each event ?>
	<?php foreach ($stamp->items as $item){ ?>

		<?php // START Event ?>
		<div class="event">

			<?php // START Date Box with Event Image as background ?>
			<?php if ($item->next): ?>

			<?php // Link to Event ?>
			<a href="<?php echo $item->url; ?>" alt="<?php echo $item->title; ?>">

			<?php // If no Event Image set ?>
			<?php if (!$item->image): ?>
			<div class="box_date">
				<span class="day"><?php echo $item->day; ?></span><br/>
				<span><?php echo $item->monthShort; ?></span>
				<span class="noimage"><?php echo JTEXT::_('COM_ICAGENDA_EVENTS_NOIMAGE'); ?></span>
			</div>
			<?php endif; ?>

			<?php // In case of Event Image ?>
			<?php if ($item->image): ?>
			<div class="box_date" style="background:url(<?php echo $item->image; ?>) no-repeat center center; background-size: cover; border: 1px solid <?php echo $item->cat_color; ?>">
				<span class="day"><?php echo $item->day; ?></span><br/>
				<span><?php echo $item->monthShort; ?></span>
			</div>
			<?php endif; ?>

			</a>
			<?php endif; ?><?php // END Date Box ?>

			<?php // START Right Content ?>
			<div class="content">

				<?php // Header (Title/Category) of the event ?>
				<div class="eventtitle">

					<?php // Title of the event ?>
					<div class="title-header">
						<h2>
							<a href="<?php echo $item->url; ?>" alt="<?php echo $item->title; ?>"><?php echo $item->titlebar; ?></a>
						</h2>
					</div>

					<?php // Category ?>
					<div class="title-cat" style="color:<?php echo $item->cat_color; ?>;">
						<?php echo $item->cat_title; ?>
					</div>

				</div>
				<div style="clear:both"></div>

				<?php // Next Date ('next' 'today' or 'last date' if no next date) ?>
				<?php if ($item->day): ?>
				<div class="nextdate">
					<strong><?php echo $item->nextDate; ?></strong>
				</div>
				<?php endif; ?>

				<?php // Location (different display, depending on the fields filled) ?>
				<?php if ($item->place_name OR $item->city): ?>
				<div class="place">

					<?php // Place name ?>
					<?php if ($item->place_name): ?><?php echo $item->place_name;?><?php endif; ?>

					<?php // If Place Name exists and city set (Google Maps). Displays Country if set. ?>
					<?php if ($item->city AND $item->place_name): ?>
						<span> - </span>
						<?php echo $item->city;?><?php if ($item->country): ?>, <?php echo $item->country;?><?php endif; ?>
					<?php endif; ?>

					<?php // If Place Name doesn't exist and city set (Google Maps). Displays Country if set. ?>
					<?php if ($item->city AND !$item->place_name): ?>
						<?php echo $item->city;?><?php if ($item->country): ?>, <?php echo $item->country;?><?php endif; ?>
					<?php endif; ?>

				</div>
				<?php endif; ?>

				<?php // Short Description ?>
				<?php if ($item->desc): ?>
				<div class="descshort">
					<?php echo $item->descShort ; ?>
				</div>
				<?php endif; ?>

				<?php // + infos Text ?>
			 	<div class="moreinfos">
			 		<a href="<?php echo $item->url; ?>" alt="<?php echo $item->title; ?>">
			 			<?php echo JTEXT::_('COM_ICAGENDA_EVENTS_MORE_INFO'); ?>
			 		</a>
			 	</div>

			</div><?php // END Right Content ?>
			<div style="clear:both"></div>

		</div><?php // END Event ?>

		<?php } ?>

		<?php // AddThis Social Sharing ?>
		<?php if ($this->atlist): ?>
		<div class="share">
			<?php echo $item->share; ?>
		</div>
		<?php endif; ?>

	</div>
	<div style="clear:both"></div>

