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
 * @version     3.2.0.4 2013-10-04
 * @since       2.0
 *------------------------------------------------------------------------------
*/

// No direct access to this file
defined('_JEXEC') or die();

//JHtml::_('behavior.tooltip');
//JHTML::_('script','system/multiselect.js',false,true);

$user	= JFactory::getUser();
$userId	= $user->get('id');

$db = JFactory::getDbo();
$query	= $db->getQuery(true);
$query->select('version AS icv, releasedate AS icd')->from('#__icagenda')->where('id = 2');
$db->setQuery($query);
$icv=$db->loadObject()->icv;
$version=$icv;
$icd=$db->loadObject()->icd;
$date=$icd;


?>

<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
		<!-- Begin Content -->
		<div class="row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<div class="span6">
						<div class="icpanel" style="background-color:#FFFFFF; border: 1px solid #D4D4D4; padding:10px; border-radius: 10px;">
							<h2 style="font-size:2em; color: Gray; text-align: center">
								<?php echo JText::_('COM_ICAGENDA_PANEL_CONTRIBUTORS');?>
							</h2>
							<!--div>
								<h4 style="color:grey; text-align: center">
									" <?php echo JText::_('COM_ICAGENDA_PANEL_THANKS');?> "
								</h4>
							</div-->
							<div>&nbsp;</div>
							<p style="margin:10px 30px; text-align:center; color: grey;">
								<i>&ldquo; <?php echo JText::_('COM_ICAGENDA_PANEL_THANKS_TEXT'); ?> &rdquo;</i>
							</p>
							<p class="small" style="margin:20px 0px; text-align:justify; color: DimGray;">
								Ervin Bizjak, bmb, Bong, Giuseppe Bosco, Carosouza, Davor Čolić, Reinhard Ekker, elirezo, E.Gkana-D.Kontogeorgis, Goncatín, jedi, jowe3, JonxDuo, KISweb, kredo9, macedorl, Kai Metsävainio, Nenad Mihajlović, MSV, mussool, NicoDeluxe, Rickard Norberg, Andrzej Opejda, Régis, J.Ribarszki, Rikard Tømte Reitan, Leland Vandervort, Wilfred van Dijk, Roland van Wanrooy, ...
							</p>
							<h3><?php echo JText::_('COM_ICAGENDA_PANEL_TRANSLATION');?></h3>
							<div style="margin-left: 20px; padding:0px; color: DimGray;">
	<img src='../media/mod_languages/images/ca.gif' class='iCflag' /> &nbsp;Catalan : mussool <br />
	<img src='../media/mod_languages/images/tw.gif' class='iCflag' /> &nbsp;Chinese (traditional) Taiwan : jedi <br />
	<img src='../media/mod_languages/images/hr.gif' class='iCflag' /> &nbsp;Croatian : Davor Čolić <br />
	<img src='../media/mod_languages/images/cz.gif' class='iCflag' /> &nbsp;Czech : Bong <br />
	<img src='../media/mod_languages/images/nl.gif' class='iCflag' /> &nbsp;Dutch : Molenwal1, wfvdijk, Walldorff <br />
	<img src='../media/mod_languages/images/en.gif' class='iCflag' /> &nbsp;English : Lyr!C <br />
	<img src='../media/mod_languages/images/us.gif' class='iCflag' /> &nbsp;English US : Lyr!C <br />
	<img src='../media/mod_languages/images/fi.gif' class='iCflag' /> &nbsp;Finnish : Kai Metsävainio <br />
	<img src='../media/mod_languages/images/fr.gif' class='iCflag' /> &nbsp;French : Lyr!C <br />
	<img src='../media/mod_languages/images/de.gif' class='iCflag' /> &nbsp;German : mPino, BMB, Wasilis <br />
	<img src='../media/mod_languages/images/el.gif' class='iCflag' /> &nbsp;Greek : E.Gkana-D.Kontogeorgis <br />
	<img src='../media/mod_languages/images/it.gif' class='iCflag' /> &nbsp;Italian : Giuseppe Bosco (giusebos) <br />
	<img src='../media/mod_languages/images/lv.gif' class='iCflag' /> &nbsp;Latvian : kredo9 <br />
	<img src='../media/mod_languages/images/no.gif' class='iCflag' /> &nbsp;Norwegian : Rikard Tømte Reitan (Rikrei) <br />
	<img src='../media/mod_languages/images/pl.gif' class='iCflag' /> &nbsp;Polish : KISweb, gienio22 <br />
	<img src='../media/mod_languages/images/pt_br.gif' class='iCflag' /> &nbsp;Portuguese/Brasil : Carosouza <br />
	<img src='../media/mod_languages/images/pt.gif' class='iCflag' /> &nbsp;Portuguese : macedorl <br />
	<img src='../media/mod_languages/images/ru.gif' class='iCflag' /> &nbsp;Russian : MSV <br />
	<img src='../media/mod_languages/images/sr.gif' class='iCflag' /> &nbsp;Serbian (latin) : Nenad Mihajlović <br />
	<img src='../media/mod_languages/images/sk.gif' class='iCflag' /> &nbsp;Slovak : J.Ribarszki <br />
	<img src='../media/mod_languages/images/sl.gif' class='iCflag' /> &nbsp;Slovenian : erbi (Ervin Bizjak) <br />
	<img src='../media/mod_languages/images/es.gif' class='iCflag' /> &nbsp;Spanish : elerizo, mPino, Goncatín <br />
	<img src='../media/mod_languages/images/sv.gif' class='iCflag' /> &nbsp;Swedish : Rickard Norberg <br />
							</div>
							<br />
						</div>
					</div>
					<div class="span1">
					</div>
					<div class="span5">
						<div style="float:right; padding:0px 0px 0px 20px;">
							<img src="../administrator/components/com_icagenda/add/image/logo_icagenda.png" />
						</div>
						<div>
							<h2 style="font-size:2em;">
								<b style="color:#cc0000;">iC</b><b style="color: #666666;">agenda<sup style="font-size:0.6em">&trade;</sup></b>&nbsp;<b style="font-size:0.5em;"></b>
							</h2>
						</div>
						<div>
							<h4>
								<?php echo JText::_('COM_ICAGENDA_INFORMATION') ?>
							</h4>
						</div>
						<div>&nbsp;</div>
						<div>&nbsp;</div>
						<div>&nbsp;</div>
						<div>&nbsp;</div>
						<div>&nbsp;</div>
						<div>&nbsp;</div>

						<h3><?php echo JText::_('iCagenda Team');?></h3>
						<p>
							<b><?php echo JText::_('COM_ICAGENDA_PANEL_LEAD_DEVELOPER');?></b><br>
							Cyril Rezé (Lyr!C) | <a href="http://www.joomlic.com" target="_blanck">www.joomlic.com</a>
						</p>
						<p>
							<b><?php echo JText::_('COM_ICAGENDA_PANEL_TEAM_1');?></b><br>
							Giuseppe Bosco (giusebos) | <a href="http://www.newideasproject.com/" target="_blanck">www.newideasproject.com</a>
						</p>
						<h3><?php echo JText::_('COM_ICAGENDA_VERSION');?></h3>
						<p>
							<?php echo $version ;?>
						</p>
						<h3><?php echo JText::_('COM_ICAGENDA_COPYRIGHT');?></h3>
						<p>
							© 2012 - <?php echo date("Y"); ?> Cyril Rezé<br/>
							<a href="http://www.joomlic.com" target="_blanck">www.Jooml!C.com</a>
						</p>
						<h3><?php echo JText::_('COM_ICAGENDA_LICENSE');?></h3>
						<p>
							<a href="http://www.gnu.org/licenses/gpl.html" target="_blanck">GPLv2 or later</a>
						</p>

					</div>
				</div>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span12">
				<tbody>
					<table style="border: 0px;">
						<tr>
							<td>
								<a href="http://www.joomlic.com/translations" target="_blanck" class="btn">
									<?php echo JText::_('COM_ICAGENDA_PANEL_TRANSLATION_PACKS_DONWLOAD');?>
								</a>
							</td>
							<td>
								<a href='http://www.joomlic.com/forum/icagenda'  target="_blanck" class="btn">
									<?php echo JText::_('COM_ICAGENDA_PANEL_HELP_FORUM'); ?>
								</a>
							</td>
						</tr>
					</table>
				</tbody>
			</div>
		</div>
	</div>

	<!-- footer -->
	<div>
		<div class="row-fluid">
			<div class="span12">
				<hr>
				<div class="row-fluid">
					<div class="span9">
						Copyright ©2012-<?php echo date("Y"); ?> joomlic.com -&nbsp;
						<?php echo JText::_('COM_ICAGENDA_PANEL_COPYRIGHT');?>&nbsp;<a href="http://extensions.joomla.org/extensions/calendars-a-events/events/events-management/22013" target="_blanck">Joomla! Extensions Directory</a>.
						<br />
						<br />
					</div>
					<div class="span3" style="text-align: right">
						<a href='http://www.joomlic.com' target='_blanck'>
							<img src="../media/com_icagenda/images/logo_joomlic.png" border="0"/>
						</a>
						<br />
						<i><b><?php echo JText::_('COM_ICAGENDA_PANEL_SITE_VISIT');?>&nbsp;<a href='http://www.joomlic.com' target='_blanck'>www.joomlic.com</a></b></i>
					</div>
				</div>
			</div>
		</div>
	</div>

