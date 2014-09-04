<?php
/**
 *------------------------------------------------------------------------------
 *  iCagenda v3 by Jooml!C - Events Management Extension for Joomla! 2.5 / 3.x
 *------------------------------------------------------------------------------
 *
 * @package LiveUpdate 2.1.5
 * @copyright Copyright (c)2010-2013 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU LGPLv3 or later <http://www.gnu.org/copyleft/lesser.html>
 *
 * @version     3.1.7 2013-08-28
 * @since       1.2.6
 */
/**
 * Specific strings iCagenda
 */

defined('_JEXEC') or die();

$stability = JText::_('LIVEUPDATE_STABILITY_'.$this->updateInfo->stability);
?>

<div class="liveupdate">

	<div id="nagscreen">
		<h2><?php echo JText::_('LIVEUPDATE_NAGSCREEN_HEAD_ICAGENDA') ?></h2>

		<p class="nagtext" style="font-weight:normal; text-align:center;"><i><?php echo JText::sprintf('LIVEUPDATE_NAGSCREEN_VERSION_ICAGENDA', $this->updateInfo->version, $stability) ?></i></p>
		<p class="nagtext" style="font-weight:normal; text-align:left; margin:0 13%;"><?php echo JText::_('LIVEUPDATE_NAGSCREEN_BODY_ICAGENDA') ?></p>
		<p class="nagtext" style="font-weight:normal; text-align:center;"><small><?php echo JText::_('LIVEUPDATE_NAGSCREEN_FOOTER_ICAGENDA') ?> <a href="http://www.joomlic.com" target="_blanck">www.joomlic.com</a></small></p>
	</div>
	<p class="liveupdate-buttons">
		<button onclick="window.location='<?php echo $this->runUpdateURL ?>'" ><?php echo JText::_('LIVEUPDATE_NAGSCREEN_BUTTON') ?></button>
	</p>

	<p class="liveupdate-poweredby">
		Powered by <a href="https://www.akeebabackup.com/software/akeeba-live-update.html">Akeeba Live Update</a>
	</p>

</div>
