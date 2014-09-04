<!--
 * Theme Pack Official
 * @name		ic_rounded
 * @template	list all dates
 * @author		Lyr!C (JoomliC)
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @updated		3.2.2 2013-10-06
 * @version		3.0
-->


<?php
// No direct access to this file
defined('_JEXEC') or die();

// List of events Template ?>
<div>


	<div class="items">
	<?php //foreach ($stamp->items as $item){ ?>

		<div class="event">
			<?php if ($item->next): ?>
			<a href="<?php echo $url; ?>" alt="<?php echo $item->title; ?>">
			<?php if (!$image): ?>

			<div class="box_date">

				<span class="day"><?php echo $item->day; ?></span><br/><?php echo $item->monthShort; ?>
				<span class="noimage"><?php echo JTEXT::_('COM_ICAGENDA_EVENTS_NOIMAGE'); ?></span>
			</div>

			<?php endif; ?>
			<?php if ($image): ?>

			<div class="box_date" style="background:url(<?php echo $image; ?>) no-repeat center center; background-size: cover; border: 1px solid <?php echo $item->cat_color; ?>">

				<span class="day"><?php echo $item->day; ?></span><br/><?php echo $item->monthShort; ?>
			</div>

			<?php endif; ?>
			</a>
			<?php endif; ?>

			<div class="content">
				<div class="eventtitle">
					<table class="table">
						<tbody>
							<tr>
								<td class="tit" valign="middle">
									<div>
										<h3>
											<a href="<?php echo $url; ?>" alt="<?php echo $item->title; ?>"><?php echo $titlebar; ?></a>
										</h3>
									</div>
								</td>
								<td class="cat" valign="middle">
									<div style="color:<?php echo $cat_color; ?>;">
										<?php echo $cat_title; ?>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div style="clear:both"></div>

				<?php if ($item->day): ?>

				<div class="nextdate"><b><?php echo $Date; ?></b>
							<?php //if ($item->evenTime): ?>
								<!--span class="evttime"><?php //echo $item->evenTime;?></span-->
							<?php //endif; ?>
				</div>

				<?php endif; ?>
			        <?php if ($item->place_name OR $item->city): ?>

				<div class="place">
					<?php if ($item->place_name): ?><?php echo $item->place_name;?><?php endif; ?>
					<?php if ($item->city AND $item->place_name): ?> - <?php echo $item->city;?><?php if ($item->country): ?>, <?php echo $item->country;?><?php endif; ?><?php endif; ?>
					<?php if ($item->city AND !$item->place_name): ?><?php echo $item->city;?><?php if ($item->country): ?>, <?php echo $item->country;?><?php endif; ?><?php endif; ?>
					</div><br/>
				<?php endif; ?>


				<?php if ($item->desc): ?>
				<div class="descshort"><?php echo $item->descShort ; ?></div>
				<?php endif; ?>

			 	<div class="moreinfos">
			 		<a href="<?php echo $url; ?>" alt="<?php echo $item->title; ?>"><?php echo JTEXT::_('COM_ICAGENDA_EVENTS_MORE_INFO'); ?></a>
			 	</div>

			</div>
			<div style="clear:both"></div>

		</div>

		<?php //} ?>


		<?php if ($this->atlist): ?>
			<div class="share"><?php echo $item->share; ?></div>
		<?php endif; ?>

	</div>
</div>
<div style="clear:both"></div>

