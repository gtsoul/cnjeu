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
 * @themepack	default
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
 * default Official Theme Pack
 *
 * @template	event_details
 * @version 	3.2.3 2013-10-20
 *
-->

<?php // Event Details Template ?>
<div>

	<?php // Back Arrow ?>
	<span class="back">
		<?php echo $item->BackArrow; ?>
	</span>

	<?php // AddThis Social Sharing ?>
	<?php echo $item->share_event; ?>

	<?php // Cleaning the DIV (AddThis) ?>
	<div style="clear:both"></div>

	<?php // Title of the event ?>
	<h2>
		<?php echo $item->title; ?>
	</h2>

	<?php // Registration button ?>
	<?php echo $item->reg; ?>

	<?php // Cleaning the DIV (registration) ?>
	<div class="clr"></div>

	<?php // Event Display ?>
	<div class="icinfo">

		<?php // Show Image of the event ?>
		<div class="image">
			<?php if ($item->imageTag): ?>
			<div><?php echo $item->imageTag; ?></div>
			<?php endif; ?>
		</div>

		<?php // Details of the event ?>
		<div class="details">
			<table>
				<tbody>

					<?php // Category ?>
					<tr><th><?php echo JTEXT::_('COM_ICAGENDA_EVENT_CAT');  ?></th><td><?php echo $item->cat_title; ?></td></tr>

					<?php // Next Date ('next' 'today' or 'last date' if no next date) ?>
					<tr><th><?php echo $item->dateText; ?></th>
						<td>
							<?php echo $item->nextDate; ?>
							<?php if ($item->evenTime): ?>
								<!--span class="evttime"><?php echo $item->evenTime;?></span-->
							<?php endif; ?>

						</td>
					</tr>

					<?php // Period Dates ('next' 'today' or 'last date' if no next date) ?>
					<?php if ($item->periodDisplay): ?>
					<!--tr><th><?php echo JTEXT::_('COM_ICAGENDA_EVENT_PERIOD'); ?></th>
						<td>
							<?php echo $item->startDate; ?> <span class="evttime"><?php echo $item->startTime; ?></span>
							 - <?php echo $item->endDate; ?> <span class="evttime"><?php echo $item->endTime; ?></span>
						</td>
					</tr-->
					<?php endif; ?>

					<?php // Place name and/or address (different display, depending on the fields filled) ?>
			        <?php if ($item->place_name OR $item->address): ?>
					<tr>
						<th><?php echo JTEXT::_('COM_ICAGENDA_EVENT_PLACE'); ?></th>
							<td>
							<?php if (($item->place_name) and (!$item->address)): ?>
							<?php echo $item->place_name; ?><?php if ($item->city): ?> - <?php echo $item->city;?><?php endif; ?>
							<?php endif; ?>
							<?php if ((!$item->place_name) and ($item->address)): ?>
							<?php echo $item->address; ?>
							<?php endif; ?>
							<?php if (($item->place_name) and ($item->address)): ?>
							<?php echo $item->place_name; ?> - <?php echo $item->address;?>
					       	<?php endif; ?>
						</td>
					</tr>
			        <?php endif; ?>

					<?php // Information ?>
					<?php if ($item->infoDetails): ?>

						<?php // Max. Nb of tickets ?>
			        	<?php if ($item->maxNbTickets): ?>
							<tr><th><?php echo JTEXT::_('ICAGENDA_REGISTRATION_FORM_PEOPLE'); ?></th><td><?php echo $item->maxReg; ?></td></tr>
			        	<?php endif; ?>

						<?php // Nb of places left ?>
			        	<?php if ($item->placeLeft): ?>
							<tr><th><?php echo JTEXT::_('COM_ICAGENDA_REGISTRATION_PLACES_LEFT'); ?></th><td><?php echo $item->placeLeft; ?></td></tr>
			        	<?php endif; ?>

						<?php // phone ?>
			        	<?php if ($item->phone): ?>
							<tr><th><?php echo JTEXT::_('COM_ICAGENDA_EVENT_PHONE'); ?></th><td><?php echo $item->phone; ?></td></tr>
			        	<?php endif; ?>

						<?php // email ?>
			        	<?php if ($item->email): ?>
							<tr><th><?php echo JTEXT::_('COM_ICAGENDA_EVENT_MAIL'); ?></th><td><?php echo $item->emailLink; ?></td></tr>
			        	<?php endif; ?>

						<?php // website ?>
			        	<?php if ($item->website): ?>
							<tr><th><?php echo JTEXT::_('COM_ICAGENDA_EVENT_WEBSITE'); ?></th><td><?php echo $item->websiteLink; ?></td></tr>
			        	<?php endif; ?>

						<?php // file attached ?>
			        	<?php if ($item->file): ?>
							<tr><th><?php echo JTEXT::_('COM_ICAGENDA_EVENT_FILE'); ?></th><td><?php echo $item->fileTag; ?></td></tr>
			        	<?php endif; ?>

			        <?php endif; ?>
				</tbody>
			</table>

		</div><?php // end div.details ?>

		<?php // Cleaning the DIV (details) ?>
		<div style="clear:both"></div>

	<?php // description text ?>
	<?php if ($item->desc): ?>
		<div id="detail-desc"><?php echo $item->description; ?></div>
	<?php endif; ?>

	<div>&nbsp;</div>

	<?php // Google Maps ?>
	<?php if ($item->coordinate): ?>
		<div id="detail-map">
			<div id="icagenda_map">
				<?php echo $item->map; ?>
			</div>
		</div>
	<?php endif; ?>

	<?php // Cleaning the DIV (text & Google Maps) ?>
		<div style="clear:both"></div>

	<div>&nbsp;</div>

	<?php // List of all dates (multi-dates and/or period from to) ?>
	<?php if ($item->datelistUl OR $item->periodDates): ?>
		<div id="detail-date-list">
			<h3 class="alldates"><?php echo JTEXT::_('COM_ICAGENDA_EVENT_DATES'); ?></h3>
			<div class="datesList">

				<?php // Period from X to X ?>
				<?php echo $item->periodDates; ?>

				<?php // Individual dates ?>
				<?php echo $item->datelistUl; ?>

			</div>
		</div>
	<?php endif; ?>

	</div><?php // end div.info ?>

	<?php // Cleaning the DIV (info) ?>
	<div style="clear:both"></div>

	<?php // List of Participants ?>
	<?php if ($item->participantList == 1) : ?>
	<div>
		<h3><?php echo $item->participantListTitle; ?></h3>
		<?php echo $item->registeredUsers; ?>
	</div>
	<?php endif; ?>

	<?php // Cleaning the DIV (list Part.) ?>
	<div style="clear:both"></div>

</div><?php // end div Event-details ?>

<?php // Cleaning the DIV (event page) ?>
<div style="clear:both"></div>
