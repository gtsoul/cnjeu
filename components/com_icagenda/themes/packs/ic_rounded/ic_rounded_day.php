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
 * @template	calendar info-tip
 * @version 	3.2.5 2013-11-11
 * @since       1.0
 *------------------------------------------------------------------------------
*/

// No direct access to this file
defined('_JEXEC') or die(); ?>

<?php // Day with event ?>
<?php if ($stamp->events) {?>

	<?php // Main Background of a day ?>

	<div class="icevent <?php echo $multi_events; ?>" style="background:<?php echo $bg_day; ?> !important; z-index:1000;">

		<?php // Color of date text depending of the category color ?>
		<a>
		<div class="<?php echo $stamp->ifToday; ?> <?php echo $bgcolor; ?>">
			<?php echo $stamp->Days; ?>
		</div>
		</a>

		<?php // Start of the Tip ?>
		<span class="spanEv">

			<?php foreach($stamp->events as $e){

				// Show image if exist
				if ($e['image']) {
					echo '<a href="'.$e['url'].'"><div class="linkTo"><span style="background: '.$e['cat_color'].';" class="img"><img src="'.$e['image'].'"/></span>';
				}
				else {
					echo '<a href="'.$e['url'].'"><div class="linkTo"><span style="background: '.$e['cat_color'].';" class="img"><div class="noimg '.$bgcolor.'">'.$e['no_image'].'</div></span>';
				}

				// Display Title (with link to event) and other infos if set (city, country)
				echo '<span class="titletip"><div>&rsaquo; '.$e['title'].'</div>';

				if ($e['city']) {
					echo '<div class="infotip">'.$e['city'];
					if (($e['country']) && ($e['city'])) {
						echo ', '.$e['country'];
					}
					if (($e['country']) AND (!$e['city'])) {
						echo $e['country'];
					}
					echo '</div>';
				}

				// Display Venue Name
				if ($e['place']) {
					echo '<div class="infotip">'.$e['place'].'</div>';
				}

				// Display Short Description
				if ($e['descShort']) {
					echo '<div class="infotip"><i>'.$e['descShort'].'</i></div>';
				}

				// Display Registration Information
				echo '<div style="clear:both"></div>';
				if (($e['maxTickets']) || ($e['registered'])) {
					echo '<div class="regButtons">';
					if ($e['maxTickets']) {
						echo '<span class="iCreg available">'.JText::_( 'MOD_ICCALENDAR_SEATS_NUMBER' ).': '.$e['maxTickets'].'</span>';
					}
					if ($e['TicketsLeft'] AND $e['maxTickets']) {
						echo '<span class="iCreg ticketsleft">'.JText::_( 'MOD_ICCALENDAR_SEATS_AVAILABLE' ).': '.$e['TicketsLeft'].'</span>';
					}
					if ($e['registered']) {
						echo '<span class="iCreg registered">'.JText::_( 'MOD_ICCALENDAR_ALREADY_REGISTERED' ).': '.$e['registered'].'</span>';
					}
					echo '</div>';
				}

				echo '</span><span class="clr"></span></div></a>';
			}
			?>
		</span>

		<?php // Display Date at the top of the info-tip ?>
		<span class="date">
			<span class="datetxt"><?php echo JTEXT::_('JDATE');  ?> : </span>&nbsp;<span class="dateformat"><?php echo $stamp->dateTitle; ?></span>
		</span>

	</div><?php // end of the day ?>


<?php // Day with no event ?>
<?php }else{ ?>
	<div class="no_event">
		<?php echo $stamp->day; ?>
	</div>
<?php } ?>
