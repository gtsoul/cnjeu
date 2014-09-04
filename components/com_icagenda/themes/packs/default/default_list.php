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
 * @template	events_list
 * @version 	3.2.3 2013-10-20
 * @since       1.0
 *------------------------------------------------------------------------------
*/

// No direct access to this file
defined('_JEXEC') or die();?>

<!--
 *
 * iCagenda by Jooml!C
 * default Official Theme Pack
 *
 * @template	events_list
 * @version 	3.2.3 2013-10-20
 *
-->

<?php // List of Events Template ?>
<div>

	<?php // Items ?>
	<div class="items">
	<?php foreach ($stamp->items as $item){ ?>

		<?php // Show event ?>
		<div class="event">
			<table class="table">
				<tr class="table">

					<?php // Left-Box with Date ?>
					<td class="leftbox">

						<?php // Display Date ?>
						<?php if ($item->next): ?>
						<div class="box_date <?php echo $item->fontColor; ?>" style="background:<?php echo $item->cat_color; ?>;">
							<div class="ic-date">

								<?php // Day ?>
								<div class="day"><?php echo $item->day; ?></div>

								<?php // Month ?>
								<div class="month"><?php echo $item->monthShort; ?></div>

								<?php // Year ?>
								<div class="year"><?php echo $item->year; ?></div>

							</div>
						</div>
					</td>
					<?php endif; ?>


					<?php // Right-Box with Infos ?>
					<td class="content">
						<div>


							<?php // Category ?>
							<span class="cat"><?php echo $item->cat_title; ?> </span>


							<?php // Event Title with link to event + Manager Icons (included in titlebar) ?>
							<h2>
								<a href="<?php echo $item->url; ?>" alt="<?php echo $item->title; ?>"><?php echo $item->titlebar; ?></a>
							</h2>


							<?php // Short Description ?>
							<?php if ($item->desc): ?>
							<span class="descshort"><?php echo $item->descShort ; ?></span>
							<?php endif; ?>

						</div>
						<?php // Cleaning the DIV ?>
						<div class="clr"></div>
					</td>
				</tr>
			</table>
		</div>

		<?php } ?>


		<?php // AddThis buttons ?>
		<?php if ($this->atlist): ?>
			<div class="share"><?php echo $item->share; ?></div>
		<?php endif; ?>

	</div>
	<?php // Cleaning the DIV ?>
	<div class="clr"></div>
</div>
<?php // Cleaning the DIV ?>
<div class="clr"></div>
