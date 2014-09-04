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
 * @template	event_registration
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
 * @template	event_registration
 * @version 	3.2.3 2013-10-20
 *
-->

<?php // Header of Registration page ?>
<div>

	<div class="items">
	<?php foreach ($stamp->items as $item){ ?>


		<?php // Show event ?>
		<div class="event">
			<table class="table">
				<tr class="table">

					<?php // Show icon (left-box) ?>
					<td class="leftbox">
					<?php if ($item->next): ?>
						<div class="box_date">
							<img src="media/com_icagenda/images/registration-48.png">
						</div>
					</td>
					<?php endif; ?>

					<?php // Show Event Details (right-box) ?>
					<td class="content">
						<div>

							<?php // Category ?>
							<span class="cat"><?php echo $item->cat_title; ?> </span>

							<?php // Event Title with link to event ?>
							<h2>
								<a href="<?php echo $item->url; ?>" alt="<?php echo $item->title; ?>"><?php echo $item->title; ?></a>
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

			<?php // Add Registration infos (places left) ?>
			<div class="reginfos">
				<?php if ($item->placeLeft): ?><?php echo JTEXT::_('COM_ICAGENDA_REGISTRATION_PLACES_LEFT');  ?>: <?php echo $item->placeLeft; ?><?php endif; ?>
			</div>


		</div>


		<?php } ?>


	</div>
</div>
