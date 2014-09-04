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
 * @template	event_details
 * @version 	3.2.3 2013-10-20
 * @since       1.0
 *------------------------------------------------------------------------------
*/

// No direct access to this file
defined('_JEXEC') or die(); ?>

<!--
 *
 * iCagenda by Jooml!C
 * ic_rounded Official Theme Pack
 *
 * @template	event_details
 * @version 	3.2.3 2013-10-20
 *
-->

<?php // Event Details Template ?>

	<?php // Back Arrow ?>
	<span class="back">
		<?php echo $item->BackArrow; ?>
	</span>

	<?php // Header (Title/Category) of the event ?>
	<div class="event-header">

		<?php // Title of the event ?>
		<div class="title-header">
			<h1>
				<?php echo $item->title; ?>
			</h1>
		</div>

		<?php // Category ?>
		<div class="title-cat" style="color:<?php echo $item->cat_color; ?>;">
			<?php echo $item->cat_title; ?>
		</div>

	</div>
	<div style="clear:both"></div>

	<p>&nbsp;</p>

	<?php // Sharing and Registration ?>
	<div>

		<?php // AddThis Social Sharing ?>
		<div style="float:left">
			<?php echo $item->share_event; ?>
		</div>

		<?php // Registration button ?>
		<div>
			<?php echo $item->reg; ?>
		</div>

	</div>
	<div style="clear:both"></div>

	<?php // Event Informations Display ?>
	<div class="icinfo">

		<?php // Show Image of the event ?>
		<div class="image">
			<?php if ($item->imageTag): ?>
			<div>
				<?php echo $item->imageTag; ?>
			</div>
			<?php endif; ?>
		</div>

		<?php // Details of the event ?>
		<div class="details">

			<?php // Next Date ('next' 'today' or 'last date' if no next date) ?>
			<strong><?php echo $item->dateText; ?>:</strong>&nbsp;<?php echo $item->nextDate; ?>

			<?php // Location (different display, depending on the fields filled) ?>
			<p>

				<?php // Place name ?>
				<?php if ($item->place_name): ?>
					<strong><?php echo JTEXT::_('COM_ICAGENDA_EVENT_PLACE'); ?>:</strong> <?php echo $item->place_name;?>
				<?php endif; ?>

				<?php // If Place Name exists and city set (Google Maps). Displays Country if set. ?>
				<?php if (($item->place_name) AND ($item->city)): ?>
					<span>&nbsp;|&nbsp;</span>
					<strong><?php echo JTEXT::_('COM_ICAGENDA_EVENT_CITY'); ?>:</strong> <?php echo $item->city;?><?php if ($item->country): ?>, <?php echo $item->country;?><?php endif; ?>
				<?php endif; ?>

				<?php // If Place Name doesn't exist and city set (Google Maps). Displays Country if set. ?>
				<?php if ((!$item->place_name) AND ($item->city)): ?>
					<strong><?php echo JTEXT::_('COM_ICAGENDA_EVENT_CITY'); ?>:</strong> <?php echo $item->city;?><?php if ($item->country): ?>, <?php echo $item->country;?><?php endif; ?>
				<?php endif; ?>

			</p>

		</div>
		<div style="clear:both"></div>

		<?php // description text ?>
		<?php if ($item->desc): ?>
		<div id="detail-desc">
			<?php echo $item->description; ?>
		<?php endif; ?>

		<?php if (!$item->desc): ?>
		<div>
		<?php endif; ?>

			<p>&nbsp;</p>

			<?php // Information ?>
			<?php if ($item->infoDetails): ?>
			<div class="information">

				<?php // Title Box Information ?>
				<div class="infoleft">
					<label><?php echo JTEXT::_('COM_ICAGENDA_EVENT_INFOS'); ?></label>
				</div>

				<?php // Information Details ?>
				<div class="infomiddle">

					<?php // Nb of tickets available ?>
					<?php if ($item->placeLeft): ?>
					<div>
						<div class="lbl">
							<?php echo JTEXT::_('COM_ICAGENDA_REGISTRATION_PLACES_LEFT'); ?>
						</div>
						<div class="data">
							<?php echo $item->placeLeft; ?>
						</div>
					</div>
					<?php endif; ?>

					<?php // Max. Nb of seats ?>
					<?php if ($item->maxNbTickets): ?>
					<div>
						<div class="lbl">
							<?php echo JTEXT::_('COM_ICAGENDA_REGISTRATION_NUMBER_PLACES'); ?>
						</div>
						<div class="data">
							<?php echo $item->maxReg; ?>
						</div>
					</div>
					<?php endif; ?>

					<?php // Phone Number ?>
					<?php if ($item->phone): ?>
					<div>
						<div class="lbl">
							<?php echo JTEXT::_('COM_ICAGENDA_EVENT_PHONE'); ?>
						</div>
						<div class="data">
							<?php echo $item->phone; ?>
						</div>
					</div>
					<?php endif; ?>

					<?php // Email ?>
					<?php if ($item->email): ?>
					<div>
						<div class="lbl">
							<?php echo JTEXT::_('COM_ICAGENDA_EVENT_MAIL'); ?>
						</div>
						<div class="data">
							<?php echo $item->emailLink; ?>
						</div>
					</div>
					<?php endif; ?>

					<?php // Website ?>
					<?php if ($item->website): ?>
					<div>
						<div class="lbl">
							<?php echo JTEXT::_('COM_ICAGENDA_EVENT_WEBSITE'); ?>
						</div>
						<div class="data">
							<?php echo $item->websiteLink; ?>
						</div>
					</div>
					<?php endif; ?>

					<?php // Address ?>
					<?php if ($item->address): ?>
					<div>
						<div class="lbl">
							<?php echo JTEXT::_('COM_ICAGENDA_EVENT_ADDRESS'); ?>
						</div>
						<div class="data">
							<?php echo $item->address; ?>
						</div>
					</div>
					<?php endif; ?>

				</div>

				<?php // file attached ?>
				<?php if ($item->file): ?>
				<div class="inforight">
					<?php echo '<label><i>'.JTEXT::_('COM_ICAGENDA_EVENT_FILE').'</i></label><b>'.$item->fileTag; ?></b>
				</div>
				<?php endif; ?>

			</div><?php // end div.details ?>
			<?php endif; ?>

		</div>
		<div style="clear:both"></div>

	</div>
	<div style="clear:both"></div>

	<p>&nbsp;</p>

	<?php // Google Maps ?>
	<?php if ($item->coordinate): ?>
	<p>&nbsp;</p>
	<div id="detail-map">
		<h3><?php echo JTEXT::_('COM_ICAGENDA_EVENT_MAP'); ?></h3><br />
		<div id="icagenda_map">
			<?php echo $item->map; ?>
		</div>
	</div>
	<div style="clear:both"></div>
	<?php endif; ?>

	<p>&nbsp;</p>

	<?php // List of all dates (multi-dates and/or period from to) ?>
	<?php if ($item->datelistUl OR $item->periodDates): ?>
	<p>&nbsp;</p>
	<div id="detail-date-list">
		<h3 class="alldates"><?php echo JTEXT::_('COM_ICAGENDA_EVENT_DATES'); ?></h3><br />
		<div class="datesList">
			<?php echo $item->periodDates; ?>
			<?php echo $item->datelistUl; ?>
		</div>
	</div>
	<div style="clear:both"></div>
	<?php endif; ?>

	<p>&nbsp;</p>

	<?php // List of Participants ?>
	<?php if ($item->participantList == 1) : ?>
	<p>&nbsp;</p>
	<div>
		<h3><?php echo $item->participantListTitle; ?></h3>
		<?php echo $item->registeredUsers; ?>
	</div>
	<div style="clear:both"></div>
	<?php endif; ?>

	<p>&nbsp;</p>
