<?php
/**
 * @package Component jVoteSystem for Joomla! 1.5 - 2.5
 * @projectsite www.joomess.de/projects/jvotesystem
 * @authors Johannes Meßmer, Andreas Fischer
 * @copyright (C) 2010 - 2012 Johannes Meßmer
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

//-- No direct access
defined('_JEXEC') or die('=;)');

?>
<table style="margin:0; padding:0; border: 0 none;width: 100%">
	<tr>
		<td style="vertical-align: top; padding-top: 10px;">
			<table cellspacing="0" cellpadding="0" border="0" width="100%">
				<tr>
					<td valign="top">
					<table class="adminlist">
						<tr>
							<td>
								<?php
								
								$link = 'index.php?option=com_jvotesystem&amp;view=boxen';
								$this->quickiconButton( $link, 'icon-48-boxen.png', JText::_( 'Boxen' ) );
								
								//$link = 'index.php?option=com_jvotesystem&amp;view=box&amp;controller=boxen&amp;layout=form&amp;hidemainmenu=1';
								$link = JUri::root(true)."/components/com_jvotesystem/assistant/index.php?interface=administrator&view=poll";
								$this->quickiconButton( $link, 'icon-48-box-add.png', JText::_( 'New_Poll' ), 1 );
								
								$link = 'index.php?option=com_jvotesystem&amp;view=categories';
								$this->quickiconButton( $link, 'icon-48-category.png', JText::_( 'Categories' ) );
								
								$link = 'index.php?option=com_jvotesystem&amp;view=category&amp;controller=categories&amp;hidemainmenu=1';
								$this->quickiconButton( $link, 'icon-48-category-add.png', JText::_( 'New_Category' ) );
								
								$link = 'index.php?option=com_jvotesystem&amp;view=comments';
								$this->quickiconButton( $link, 'icon-48-comments.png', JText::_( 'Comments' ) );
								
								$link = 'index.php?option=com_jvotesystem&amp;view=comment&amp;controller=comments&amp;layout=form&amp;hidemainmenu=1';
								$this->quickiconButton( $link, 'icon-48-comment-add.png', JText::_( 'New_Comment' ) );
								
								$link = 'index.php?option=com_jvotesystem&amp;view=users';
								$this->quickiconButton( $link, 'icon-48-users.png', JText::_( 'Users' ) );
								
								$link = 'index.php?option=com_jvotesystem&amp;view=bbcodes';
								$this->quickiconButton( $link, 'icon-48-generic.png', JText::_( 'BBCodes' ) );
								
								$link = 'http://joomess.de/forum/jvotesystem.html';
								$this->quickiconButton( $link, 'icon-48-forum.png', JText::_( 'Forum' ) , 0 , "_blank");
								
								$access =& VBAccess::getInstance();
								if($access->isUserAllowedToConfig()) {
									if(version_compare( JVERSION, '1.6.0', 'lt' ))
										$link = 'index.php?option=com_config&controller=component&component=com_jvotesystem&path=';
									else
										$link = 'index.php?option=com_config&view=component&component=com_jvotesystem&path=&tmpl=component';
									$this->quickiconButton( $link, 'icon-48-options.png', JText::_( 'Options' ), 1 );
								}
								?>
							</td>
						</tr>
					</table>
					</td>
				</tr>
			</table>
			<table cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-top: 10px;">
				<tr><td>
					<table class="adminlist">
						<tr>
							<td>
								<h3 style="text-align:center;"><?php echo JText::_("VOTES_PER_DAY_SURVEYS");?></h3>
								<?php echo $this->charts->getBackendChart('votesgoogle'); ?>
							</td>
						</tr>
					</table>
				</td></tr>
			</table>
		</td>
		<td style="width: 400px;vertical-align:top;">
			<div id="JSScriptUpdateInfoBar"> </div>
			<table cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-top: 10px;">
				<tr>
					<td>
						<table class="adminlist">
							<tr>
								<td>
									<h2>jVoteSystem</h2>
									<p><?php echo JText::_('JVOTESYSTEM_COMPONENT_DESC');?></p>
									<?php
										$link = 'http://www.joomess.de/projects/jvotesystem.html';
										jVoteSystemViewjVoteSystem::quickiconButton( $link, 'icon-48-website.png', JText::_( 'Projektseite' ) , 0 , "_blank");
										
										$link = 'http://extensions.joomla.org/extensions/contacts-and-feedback/polls/15859';
										jVoteSystemViewjVoteSystem::quickiconButton( $link, 'icon-48-rating.png', JText::_( 'WRITE_REVIEW' ) , 0 , "_blank");
										
										$link = 'http://joomess.de/projects/jvotesystem/download.html';
										jVoteSystemViewjVoteSystem::quickiconButton( $link, 'icon-48-cart.png', JText::_( 'BUY_COPYRIGHT_FREE' ) , 0 , "_blank");
									?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<?php $this->general->getAdminFooter(); ?>