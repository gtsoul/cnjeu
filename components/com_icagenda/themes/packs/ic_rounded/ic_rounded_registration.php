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
 * ic_rounded Official Theme Pack
 *
 * @template	event_registration
 * @version 	3.2.3 2013-10-20
 *
-->

<?php // Header of Registration page ?>
	<div class="items">

		<?php foreach ($stamp->items as $item){ ?>
		<div class="eventtitle">

			<?php // Title - Header ?>
			<div class="title-header">
				<h3>
					<?php if ($item->next): ?>
					<span style="padding:10px">
						<img src="media/com_icagenda/images/registration-48.png">
					</span>
					<?php endif; ?>
					<a href="<?php echo $item->url; ?>" alt="<?php echo $item->title; ?>"><?php echo $item->title; ?></a>
				</h3>
			</div>

			<?php // Category - Header ?>
			<div class="title-cat">
				<span style="color:<?php echo $item->cat_color; ?>;">
					<?php echo $item->cat_title; ?>
				</span>
			</div>

			<?php // Tickets Left - Header ?>
			<?php if ($item->placeLeft): ?>
			<div class="reginfos">
				<?php echo JTEXT::_('COM_ICAGENDA_REGISTRATION_PLACES_LEFT');  ?>: <?php echo $item->placeLeft; ?>
			</div>
			<?php endif; ?>

		</div>
		<?php } ?>
	</div>
