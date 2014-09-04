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
 * @version     3.2.6 2013-11-15
 * @since       1.0
 *------------------------------------------------------------------------------
*/

// No direct access to this file
defined('_JEXEC') or die();

$user	= JFactory::getUser();
$userId	= $user->get('id');

$params = JComponentHelper::getParams( 'COM_ICAGENDA' );
$version = $params->get('version');
$icsys = $params->get('icsys');


if (version_compare(phpversion(), '5.3.0', '<')) {
	if(version_compare(JVERSION, '3.0', 'lt')) {
		JError::raiseWarning( 100, ''.JText::sprintf('COM_ICAGENDA_YOUR_PHP_VERSION_IS', phpversion()).'<br />'.JText::_('COM_ICAGENDA_PHP_VERSION_JOOMLA_RECOMMENDED').' ( '.JText::_('IC_READMORE').': <a href="http://www.joomla.org/technical-requirements.html" target="_blanck">http://www.joomla.org/technical-requirements.html</a> )<br />'.JText::_('COM_ICAGENDA_PHP_VERSION_ICAGENDA_RECOMMENDATION').'' );
	} else {
		JError::raiseWarning( 100, '<span class="icon-warning"></span><b> '.JText::sprintf('COM_ICAGENDA_YOUR_PHP_VERSION_IS', phpversion()).'</b><br />'.JText::_('COM_ICAGENDA_PHP_VERSION_JOOMLA_RECOMMENDED').' ( '.JText::_('IC_READMORE').': <a href="http://www.joomla.org/technical-requirements.html" target="_blanck">http://www.joomla.org/technical-requirements.html</a> )<br />'.JText::_('COM_ICAGENDA_PHP_VERSION_ICAGENDA_RECOMMENDATION').'' );
	}
}

?>
<div id="j-main-container">
	<?php JHTML::_('behavior.modal'); ?>
	<!-- Begin Content -->
	<div class="row-fluid icpanel">
		<div class="span12">
			<div class="row-fluid">
				<div class="span6">
					<div class="row-fluid">
						<div class="span6" style="text-align: center">
							<tbody>
								<table>
									<tr>
										<td colspan="2">
											<h3><?php echo JText::_('COM_ICAGENDA_TITLE_CATEGORIES'); ?></h3>
										</td>
									</tr>
									<tr>
										<td>
											<div class="icon right">
												<a href="index.php?option=com_icagenda&view=categories">
													<img alt="" src="../media/com_icagenda/images/all_cats-48.png">
													<span class="iconText"><?php echo JText::_( 'COM_ICAGENDA_PANEL_CATEGORY' ); ?></span>
												</a>
											</div>
										</td>
										<td>
											<div class="icon left">
												<a href="index.php?option=com_icagenda&view=category&layout=edit">
													<img alt="" src="../media/com_icagenda/images/new_cat-48.png">
													<span class="iconText"><?php echo JText::_( 'COM_ICAGENDA_PANEL_NEW_CATEGORY' ); ?></span>
												</a>
											</div>
										</td>
									</tr>
								</table>
							</tbody>
						</div>

						<div class="span6" style="text-align: center">
				    		<tbody>
				    			<table>
				    				<tr>
				    					<td colspan="2">
											<h3><?php echo JText::_('COM_ICAGENDA_TITLE_EVENTS'); ?></h3>
										</td>
									</tr>
				    				<tr>
	 				   					<td>
											<div class="icon right">
												<a href="index.php?option=com_icagenda&view=events">
													<img alt="" src="../media/com_icagenda/images/all_events-48.png">
													<span class="iconText"><?php echo JText::_( 'COM_ICAGENDA_PANEL_EVENTS' ); ?></span>
												</a>
											</div>
										</td>
	 				   					<td>
											<div class="icon left">
												<a href="index.php?option=com_icagenda&view=event&layout=edit">
													<img alt="" src="../media/com_icagenda/images/new_event-48.png">
													<span class="iconText"><?php echo JText::_( 'COM_ICAGENDA_PANEL_NEW_EVENT' ); ?></span>
												</a>
											</div>
										</td>
									</tr>
								</table>
							</tbody>
						</div>

					</div>
					<div class="row-fluid">

						<div class="span6" style="text-align: center">
				    		<tbody>
				    			<table>
				    				<tr>
				    					<td colspan="2">
											<h3><?php echo JText::_('COM_ICAGENDA_TITLE_REGISTRATION'); ?></h3>
										</td>
									</tr>
				    				<tr>
	 				   					<td>
											<div class="icon right">
												<a href="index.php?option=com_icagenda&view=registrations">
													<img alt="" src="../media/com_icagenda/images/registration-48.png">
													<span class="iconText"><?php echo JText::_( 'COM_ICAGENDA_PANEL_REGISTRATION' ); ?></span>
												</a>
											</div>
										</td>
	 				   					<td>
											<div class="icon left">
												<a href="index.php?option=com_icagenda&view=mail&layout=edit">
													<img alt="" src="../media/com_icagenda/images/newsletter-48.png">
													<span class="iconText"><?php echo JText::_( 'COM_ICAGENDA_PANEL_NEWSLETTER' ); ?></span>
												</a>
											</div>
										</td>
									</tr>
								</table>
							</tbody>
						</div>

						<div class="span6" style="text-align: center">
				    		<tbody>
				    			<table>
				    				<tr>
				    					<td colspan="2">
											<h3><?php echo JText::_('COM_ICAGENDA_GLOBAL_PARAMS_LABEL'); ?></h3>
										</td>
									</tr>
				    				<tr>
	 				   					<td>
											<div class="icon right">
<?php if(version_compare(JVERSION, '3.0', 'ge')) : ?>
												<?php
													$redirectUrl = 'index.php?option=com_icagenda&view=icagenda';
													$redirectUrl = urlencode(base64_encode($redirectUrl));
												?>
												<!--a href="index.php?option=com_config&view=component&component=com_icagenda&path=&return=<?php echo $redirectUrl; ?>"-->
												<a href="index.php?option=com_config&view=component&component=com_icagenda&path=&return=<?php echo base64_encode(JURI::getInstance()->toString()) ?>">

<?php else : ?>
												<a href="index.php?option=com_config&view=component&component=com_icagenda&path=&tmpl=component"
													class="modal"
													rel="{handler: 'iframe', size: {x: 870, y: 550}}">
<?php endif; ?>
													<img src="../media/com_icagenda/images/technical_requirements-48.png">
													<span class="iconText"><?php echo JText::_('JTOOLBAR_OPTIONS') ?></span>
												</a>
											</div>
										</td>
	 				   					<td>
											<div class="icon left">
												<a href="index.php?option=com_icagenda&view=themes">
													<img alt="" src="../media/com_icagenda/images/themes-48.png">
													<span class="iconText"><?php echo JText::_( 'COM_ICAGENDA_PANEL_THEMES' ); ?></span>
												</a>
											</div>
										</td>
									</tr>
								</table>
							</tbody>
						</div>

					</div>
					<div class="row-fluid">

						<div class="span6" style="text-align: center">
				    		<tbody>
				    			<table>
				    				<tr>
				    					<td colspan="2">
											<h3><?php echo JText::_('COM_ICAGENDA_PANEL_UPDATE_AND_INFOS'); ?></h3>
										</td>
									</tr>
				    				<tr>
	 				   					<td>
											<div class="icon right">
												<a href="index.php?option=com_icagenda&view=info">
													<img src="../media/com_icagenda/images/info-48.png">
													<span class="iconText"><?php echo JText::_( 'COM_ICAGENDA_INFO' ); ?></span>
												</a>
											</div>
										</td>
	 				   					<td class="left">
											<?php echo LiveUpdate::getIcon(); ?>
										</td>
									</tr>
								</table>
							</tbody>
						</div>

						<div class="span6" style="text-align: center">

						</div>

					</div>
					<?php if ($icsys == 'core') : ?>
					<div class="row-fluid">

						<div class="span12">
							<div class="alert alert-block alert-info">
							<?php if(version_compare(JVERSION, '3.0', 'ge')) : ?>
								<button type="button" class="close" data-dismiss="alert">×</button>
							<?php endif; ?>
								<p>&nbsp;</p>
								<div style="font-weight: bold; color: #555555;">
									<p>
										<?php echo JText::_('COM_ICAGENDA_PANEL_FREE_VERSION') ?><br/>
										<?php echo JText::_('COM_ICAGENDA_PANEL_PRO_VERSION') ?>:
										<?php echo JText::_('COM_ICAGENDA_PANEL_PRO_MODULE_IC_EVENT_LIST') ?>
									</p>
								</div>
								<div style="display:none;">
									<div id="loadDiv" style="background-color:#F4F4F4;">
										<table style="width:600px; height:350px;" cellpadding="0" cellspacing="0">
											<tbody>
												<tr>
													<td style="text-align: center; height:140px;" rowspan="1" colspan="3">
														&nbsp;&nbsp;&nbsp;<img src="../media/com_icagenda/images/iconicagenda48.png" />
													</td>
												</tr>
												<tr>
													<td style="text-align: right; width: 280px; height:60px;">
														<form action="https://secure.shareit.com/shareit/checkout.html?PRODUCT[300582128]=1&stylefrom=300582128" method="post" target="_blanck">
															<input type="submit" class="btn" width="120px" value="<?php echo JText::_( 'COM_ICAGENDA_PURCHASE_1_YEAR' ); ?>" />
														</form>
													</td>
													<td style="width: 40px; height:60px;">
													</td>
													<td style="width: 280px; height:60px;">
														<form action="https://secure.shareit.com/shareit/checkout.html?PRODUCT[300579672]=1&stylefrom=300579672" method="post" target="_blanck">
															<input type="submit" class="btn" value="<?php echo JText::_( 'COM_ICAGENDA_PURCHASE_UNLIMITED' ); ?>" />
														</form>
													</td>
												</tr>
												<tr>
													<td style="text-align: center; height:50px;" colspan="3">
														<a href="http://www.joomlic.com/extensions/icagenda" alt ="<?php echo JText::_( 'COM_ICAGENDA_INFO' ); ?>" target="_blanck"><?php echo JText::_( 'COM_ICAGENDA_VERSIONS_COMPARISON' ); ?></a>
													</td>
												</tr>
												<tr>
													<td style="text-align: center;" rowspan="1" colspan="3">
														<div>
															<p>
																<img src="../media/com_icagenda/images/payment/icon_cca.gif" border="0"/>
																<img src="../media/com_icagenda/images/payment/icon_pal.gif" border="0"/>
																<img src="../media/com_icagenda/images/payment/icon_wtr.gif" border="0"/>
																<img src="../media/com_icagenda/images/payment/icon_chk.gif" border="0"/>
															</p>
														</div>
														<div>
															<img src="http://a124.e.akamai.net/f/124/5462/2d/images.element5.com/shareit/images/website/images/shareit_ani.gif" border="0"/>
														</div>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>

								<p>
									&nbsp;
								</p>
								<div>
									<p style="text-align: center;">
										<a href="#loadDiv" class="modal" rel="{size: {x: 600, y: 350}}">
											<input type="submit" class="btn" value="<?php echo JText::_( 'COM_ICAGENDA_PURCHASE' ); ?>" />
										</a>
										<!--a href="http://www.joomlic.com/extensions/icagenda" alt ="<?php echo JText::_( 'COM_ICAGENDA_INFO' ); ?>" target="_blanck">
											<?php echo JText::_( 'COM_ICAGENDA_INFO' ); ?>
										</a-->
									</p>
									<p style="text-align: center; font-size:11px;">
										<a href="http://www.joomlic.com/extensions/icagenda" alt ="<?php echo JText::_( 'COM_ICAGENDA_INFO' ); ?>" target="_blanck"><?php echo JText::_( 'COM_ICAGENDA_VERSIONS_COMPARISON' ); ?></a>
									</p>
								</div>

							</div>

						</div><!--end span12-->

					</div><!--end row-->
					<?php endif; ?>

				</div><!--end span 6-->
				<div class="span1">
				</div><!--end span 1-->
				<div class="span5">
					<div class="span12">

					<?php
						$db = JFactory::getDbo();
						$query	= $db->getQuery(true);
						//$query->select('version AS icv, releasedate AS icd')->from('#__icagenda')->where('id = 1');
						$query->select('version AS icv, releasedate AS icd')->from('#__icagenda')->where('id = 2');
						$db->setQuery($query);
						$icv=$db->loadObject()->icv;
						$release=$icv;
						$icd=$db->loadObject()->icd;
						$date=$icd;

						$translator=JText::_('COM_ICAGENDA_TRANSLATOR');
					?>

					<div style="float:right; padding:0px 0px 0px 20px;">
						<img src="../administrator/components/com_icagenda/add/image/logo_icagenda.png" />
					</div>
					<div>
						<h2 style="font-size:2em;">
							<b style="color:#cc0000;">iC</b><b style="color: #666666;">agenda<sup style="font-size:0.6em">&trade;</sup></b><?php echo $version;?>
						</h2>
					</div>
					<div>
						<h4>
							<?php echo JText::_('COM_ICAGENDA_COMPONENT_DESC') ?>
						</h4>
					</div>

					<div class="small">
						<?php echo JText::_('COM_ICAGENDA_FEATURES_BACKEND') ?><br />
						<?php echo JText::_('COM_ICAGENDA_FEATURES_FRONTEND') ?>
					</div>

					<div>
						&nbsp;
					</div>

					<div style="font-size:0.9em" class="blockbtn">
						<?php echo JText::_('COM_ICAGENDA_PANEL_VERSION');?>:&nbsp;<b><?php echo $release ;?></b> | <?php echo JText::_('COM_ICAGENDA_PANEL_DATE');?>:&nbsp;<b><?php echo $date ;?></b>&nbsp;&nbsp;

						<!-- UPDATE LOG :: BEGIN -->
						<a href="#" id="btupdatelogs" class="btn"><?php echo JText::_('COM_ICAGENDA_PANEL_UPDATE_LOGS') ?></a>
					</div>
					<?php
						if(version_compare(JVERSION, '3.0', 'lt')) {
							JHTML::_('behavior.mootools');
						} else {
							JHtml::_('behavior.framework');
						}
						JHtml::_('behavior.modal');

						$script = <<<ENDSCRIPT
						window.addEvent( 'domready' ,  function() {
							$('btupdatelogs').addEvent('click', showUpdateLogs);
						});

						function showUpdateLogs()
						{
							SqueezeBox.open(
								$('icagenda-updatelogs'), {
									handler: 'adopt',
									size: {
										x: 600,
										y: 350
									}
								}
							);
						}
ENDSCRIPT;
						$document = JFactory::getDocument();
						$document->addScriptDeclaration($script,'text/javascript');
					?>

					<div style="display:none;">
						<div id="icagenda-updatelogs">
							<?php
								require_once dirname(__FILE__).'/color.php';
								echo iCagendaUpdateLogsColoriser::colorise(JPATH_COMPONENT_ADMINISTRATOR.'/UPDATELOGS.php');
							?>
						</div>
					</div>
					<!-- UPDATE LOG :: END -->

					<br/>
					<?php
						$urlposter = '../media/com_icagenda/images/video_poster_icagenda.jpg';
					?>

					<div>
						&nbsp;
					</div>
					<div>
						&nbsp;
					</div>

					<div onclick="thevid=document.getElementById('thevideo'); thevid.style.display='block'; this.style.display='none'">
						<img style="cursor: pointer;" src="<?php echo $urlposter; ?>" alt=""  width="100%" />
					</div>

					<div id="thevideo" style="display: none;">
						<?php
							jimport('joomla.application.component.helper'); // Import component helper library
							$icagendaParams = JComponentHelper::getParams('com_icagenda');
							$icfolder = $icagendaParams->get('icsys');
						?>
						<iframe src="http://www.joomlic.com/_icagenda/<?php echo $icfolder; ?>/tutorial_video_cp.html" frameborder="0" width="100%" height="340" scrolling="no"></iframe>

					</div>

					<div style="color:#333; margin-top: 5px; font-size: 0.8em;">
						© <?php echo date("Y"); ?> <?php echo JText::_('COM_ICAGENDA_VIDEO_TUTORIALS');?> - Giuseppe Bosco (giusebos) | <a href="http://www.newideasproject.com/" target="_blanck">www.newideasproject.com</a>
					</div>

					<div style="color:#333; margin-top: 5px; font-size: 0.8em; line-height:14px; height:30px;">
						<a href="http://www.youtube.com/user/iCagenda" target="_blanck"><img src='../media/com_icagenda/images/youtube_iCagenda.png' style='vertical-align:bottom;' /></a> : <a href="http://www.youtube.com/user/iCagenda" target="_blanck"><?php echo JText::_('COM_ICAGENDA_VIDEO_TUTORIALS');?></a>
					</div>

					<div>
						&nbsp;
					</div>
					</div>

				</div>

			</div>
		</div>
	</div>

	<div class="row-fluid">
		<div class="span12">
			<div class="row-fluid">
				<div class="span12">
					<h3>23&nbsp;<?php echo JText::_('COM_ICAGENDA_PANEL_TRANSLATION_PACKS');?></h3>
					<p>
						<?php
							if(version_compare(JVERSION, '3.0', 'lt')) {
								$iCtag = '::';
							} else {
								$iCtag = '<br>';
							}
						?>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Catalan
							<?php echo $iCtag;?><?php echo $translator;?>: mussool " >
							<img src="../media/mod_languages/images/ca.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Chinese (traditional) Taiwan
							<?php echo $iCtag;?><?php echo $translator;?>: jedi " >
							<img src="../media/mod_languages/images/tw.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Croatian
							<?php echo $iCtag;?><?php echo $translator;?>: Davor Čolić " >
							<img src="../media/mod_languages/images/hr.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Czech
							<?php echo $iCtag;?><?php echo $translator;?>: Bong " >
							<img src="../media/mod_languages/images/cz.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Dutch
							<?php echo $iCtag;?><?php echo $translator;?>: Molenwal1, wfvdijk, Walldorff " >
							<img src="../media/mod_languages/images/nl.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" English
							<?php echo $iCtag;?><?php echo $translator;?>: Lyr!C " >
							<img src="../media/mod_languages/images/en.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" English US
							<?php echo $iCtag;?><?php echo $translator;?>: Lyr!C " >
							<img src="../media/mod_languages/images/us.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Finnish
							<?php echo $iCtag;?><?php echo $translator;?>: Kai Metsävainio " >
							<img src="../media/mod_languages/images/fi.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" French
							<?php echo $iCtag;?><?php echo $translator;?>: Lyr!C " >
							<img src="../media/mod_languages/images/fr.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" German
							<?php echo $iCtag;?><?php echo $translator;?>: mPino, BMB, Wasilis " >
							<img src="../media/mod_languages/images/de.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Greek
							<?php echo $iCtag;?><?php echo $translator;?>: E.Gkana-D.Kontogeorgis " >
							<img src="../media/mod_languages/images/el.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Italian
							<?php echo $iCtag;?><?php echo $translator;?>: Giuseppe Bosco (giusebos) " >
							<img src="../media/mod_languages/images/it.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Latvian
							<?php echo $iCtag;?><?php echo $translator;?>: kredo9 " >
							<img src="../media/mod_languages/images/lv.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Norwegian
							<?php echo $iCtag;?><?php echo $translator;?>: Rikard Tømte Reitan " >
							<img src="../media/mod_languages/images/no.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Polish
							<?php echo $iCtag;?><?php echo $translator;?>: KISweb, gienio22 " >
							<img src="../media/mod_languages/images/pl.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Portuguese/Brasil
							<?php echo $iCtag;?><?php echo $translator;?>: Carosouza " >
							<img src="../media/mod_languages/images/pt_br.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Portuguese
							<?php echo $iCtag;?><?php echo $translator;?>: macedorl " >
							<img src="../media/mod_languages/images/pt.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Russian
							<?php echo $iCtag;?><?php echo $translator;?>: MSV " >
							<img src="../media/mod_languages/images/ru.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Serbian (latin)
							<?php echo $iCtag;?><?php echo $translator;?>: Nenad Mihajlović " >
							<img src="../media/mod_languages/images/sr.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Slovak
							<?php echo $iCtag;?><?php echo $translator;?>: J.Ribarszki " >
							<img src="../media/mod_languages/images/sk.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Slovenian
							<?php echo $iCtag;?><?php echo $translator;?>: erbi (Ervin Bizjak) " >
							<img src="../media/mod_languages/images/sl.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Spanish
							<?php echo $iCtag;?><?php echo $translator;?>: elerizo, mPino, Goncatín " >
							<img src="../media/mod_languages/images/es.gif" border="0" alt="Tooltip"/>
						</span>
						<span rel="tooltip" data-placement="right" class="editlinktip hasTip" title=" Swedish
							<?php echo $iCtag;?><?php echo $translator;?>: Rickard Norberg " >
							<img src="../media/mod_languages/images/sv.gif" border="0" alt="Tooltip"/>
						</span>
					</p>
				</div>
			</div>
		</div>
	</div>

	<div class="row-fluid">
		<div class="span12">
			<tbody>
				<table style="width: 100%; border: 0px;">
					<tr>
						<td>
							<a href="http://www.joomlic.com/translations" target="_blanck" class="btn">
								<?php echo JText::_('COM_ICAGENDA_PANEL_TRANSLATION_PACKS_DONWLOAD');?>
							</a>
						</td>
						<td style="text-align:right; vertical-align: bottom;">
							<a href='http://www.joomlic.com/forum/icagenda'  target="_blanck" class="btn">
								<?php echo JText::_('COM_ICAGENDA_PANEL_HELP_FORUM'); ?>
							</a>
						</td>
					</tr>
				</table>
			</tbody>
		</div>
	</div>

	<hr>

	<div class="row-fluid">
		<div class="span12">
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
