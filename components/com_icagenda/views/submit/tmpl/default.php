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
 * @version     3.2.4 2013-10-28
 * @since       3.2.0
 *------------------------------------------------------------------------------
*/

// No direct access to this file
defined('_JEXEC') or die();

?>
<!--
 * - - - - - - - - - - - - - -
 * iCagenda 3.2.7 by Jooml!C
 * - - - - - - - - - - - - - -
 * @copyright	Copyright (C) 2012-2013 JOOMLIC - All rights reserved.
 *
-->
<?php

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');



$user = JFactory::getUser();

$u_id=$user->get('id');
$u_mail=$user->get('email');

// logged-in Users: Name/User Name Option
$nameJoomlaUser = JComponentHelper::getParams('com_icagenda')->get('nameJoomlaUser', 1);
if ($nameJoomlaUser == 1) {
	$u_name=$user->get('name');
} else {
	$u_name=$user->get('username');
}

// Autofill name and email if registered user log in
$autofilluser = JComponentHelper::getParams('com_icagenda')->get('autofilluser', 1);
if ($autofilluser != 1) {
	$u_name='';
	$u_mail='';
}

//$themeform = $this->template.'_form';

$theme = $this->template;
$infoimg = JURI::root().'components/com_icagenda/themes/packs/default/images/info.png';

JText::script('COM_ICAGENDA_TERMS_OF_SERVICE_NOT_CHECKED_SUBMIT_EVENT');
JText::script('COM_ICAGENDA_FORM_NO_DATES_ALERT');

// Global Options
$iCparams = JComponentHelper::getParams('com_icagenda');
$tos = $iCparams->get('tos', 1);

$accessDefault = array('2');
$submitAccess = $iCparams->get('submitAccess', $accessDefault);

// Get Content of the page for not logged-in users
$NotLoginDefault = JText::_( 'COM_ICAGENDA_EVENT_SUBMISSION_ACCESS' ).'<br />';
$submitNotLogin = $iCparams->get('submitNotLogin', '');
if ($submitNotLogin == 2) {
	$submitNotLogin_Content = $iCparams->get('submitNotLogin_Content', $NotLoginDefault);
} else {
	$submitNotLogin_Content = $NotLoginDefault;
}

// Get Content of the page for not authorised logged-in users
$NoRightsDefault = JText::_( 'COM_ICAGENDA_EVENT_SUBMISSION_NO_RIGHTS' ).'<br />';
$submitNoRights = $iCparams->get('submitNoRights', '');
if ($submitNoRights == 2) {
	$submitNoRights_Content = $iCparams->get('submitNoRights_Content', $NoRightsDefault);
} else {
	$submitNoRights_Content = $NoRightsDefault;
}

// Event Params
$params = $this->form->getFieldsets('params');

// ZOOM
$zoom='16';
// HYBRID, ROADMAP, SATELLITE, TERRAIN
$mapTypeId='ROADMAP';

$coords='0, 0';
$oldcoordinate=$coords;
$lat='0';
$lng='0';
//if (($oldcoordinate == NULL) && ($lat == '0') && ($lng == '0')) { $zoom='1'; }
$zoom='0';


// Get User Access Levels
$userLevels = $user->getAuthorisedViewLevels();

//		if(version_compare(JVERSION, '3.0', 'lt')) {
//			$userGroups = $user->getAuthorisedGroups();
//		} else {
//			$userGroups = $user->groups;
//		}

// Control: if not login, and submission form not "public"
if ( !$u_id AND !in_array('1', $submitAccess )) {
	echo '<div id="icagenda">';
	echo $submitNotLogin_Content;
	echo '</div>';

	return false;
}

// Control: if access level, or Super User
$AccessForm = false;
foreach ($submitAccess AS $ac) {
	if ( in_array($ac, $userLevels )) {
		$AccessForm = true;
	}
}
if (!$AccessForm) {
	echo '<div id="icagenda">';
	echo $submitNoRights_Content;
	echo '</div>';

	return false;
}
?>


<style type="text/css" media="screen">

legend {
		font-weight:bold;

}

</style>
<script type="text/javascript"><!--

 function checkAgree() {
     var agree = document.getElementById('formAgree');
     if (!agree.checked) {
        alert(Joomla.JText._('COM_ICAGENDA_TERMS_OF_SERVICE_NOT_CHECKED_SUBMIT_EVENT'));
        return false;
     }
     var startDate = document.getElementById('startdate');
     var Dates = document.getElementById('dates_id');
     if ((startDate.value == '') && (Dates.value == '')) {
        alert(Joomla.JText._('COM_ICAGENDA_FORM_NO_DATES_ALERT'));
        return false;
     }


     return true;
 }
--></script>


<div id="icagenda<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
<?php if ($this->params->get('show_page_heading', 1)) : ?>
	<h1 class="componentheading">
	<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
<?php endif; ?>
	<form name="submitevent" action="<?php echo JRoute::_('index.php'); ?>"  id="icagenda_form" method="post" class="icagenda_form"  enctype="multipart/form-data" onsubmit="return checkAgree();">
		<legend><?php echo JText::_('COM_ICAGENDA_LEGEND_USERINFOS'); ?></legend>
		<div class="fieldset">
			<div>
				<div>

					<label><?php echo JText::_( 'ICAGENDA_REGISTRATION_FORM_NAME' ); ?> *</label>
					<?php if ($u_name){
						echo '<input type="text" value="'.$u_name.'" disabled="disabled" />
						<input type="hidden" name="username" value="'.$u_name.'" />';
					}else{
						echo '<input type="text" name="username" value="" size="30" required="true" />';
					}?>
					<span class="formInfo">
						<a class="ictip"><img src="<?php echo $infoimg; ?>" />
						<span>
							<span class="title">
								<?php echo JText::_( 'ICAGENDA_REGISTRATION_FORM_NAME' ); ?>
							</span>
							<div class="text">
								<?php echo JText::_( 'ICAGENDA_REGISTRATION_FORM_NAME_DESC' ); ?>
							</div>
						</span>
						</a>
					</span>
				</div>

				<div>

					<label><?php echo JText::_( 'ICAGENDA_REGISTRATION_FORM_EMAIL' ); ?> *</label>
					<?php if ($u_mail){
						echo '<input type="text" value="'.$u_mail.'" disabled="disabled" />
						<input type="hidden" name="created_by_email" value="'.$u_mail.'" />';
					}else{
						echo '<input type="text" name="created_by_email" value="" size="30" required="true" />';
					}?>
					<span class="formInfo">
						<a class="ictip"><img src="<?php echo $infoimg; ?>" />
						<span>
							<span class="title">
								<?php echo JText::_( 'ICAGENDA_REGISTRATION_FORM_EMAIL' ); ?>
							</span>
							<div class="text">
								<?php echo JText::_( 'COM_ICAGENDA_SUBMIT_FORM_USER_EMAIL_DESC' ); ?>
							</div>
						</span>
						</a>
					</span>
				</div>

			</div>
		</div>

		<div>&nbsp;</div>

		<legend><?php echo JText::_('COM_ICAGENDA_LEGEND_NEW_EVENT'); ?></legend>
		<div class="fieldset">
			<div>

				<input type="hidden" name="id" size="20" required="true" />
				<div>
					<label><?php echo JText::_( 'COM_ICAGENDA_FORM_LBL_EVENT_TITLE' ); ?> *</label>
					<input type="text" name="title" size="40" required="true" />
					<span class="formInfo">
						<a class="ictip"><img src="<?php echo $infoimg; ?>" />
						<span>
							<span class="title">
								<?php echo JText::_( 'COM_ICAGENDA_FORM_LBL_EVENT_TITLE' ); ?>
							</span>
							<div class="text">
								<?php echo JText::_( 'COM_ICAGENDA_FORM_DESC_EVENT_TITLE' ); ?>
							</div>
						</span>
						</a>
					</span>
				</div>
				<div>

<label><?php echo JText::_( 'COM_ICAGENDA_FORM_LBL_EVENT_CATID' ); ?> *</label>
					<?php echo $this->form->getInput('catid'); ?>
					<span class="formInfo">
						<a class="ictip"><img src="<?php echo $infoimg; ?>" />
						<span>
							<span class="title">
								<?php echo JText::_( 'COM_ICAGENDA_FORM_LBL_EVENT_CATID' ); ?>
							</span>
							<div class="text">
								<?php echo JText::_( 'COM_ICAGENDA_FORM_DESC_EVENT_CATID' ); ?>
							</div>
						</span>
						</a>
					</span>
				</div>
				<div>

<label><?php echo JText::_( 'COM_ICAGENDA_FORM_LBL_EVENT_IMAGE' ); ?></label>
					<?php echo $this->form->getInput('image'); ?>
					<span class="formInfo">
						<a class="ictip"><img src="<?php echo $infoimg; ?>" />
						<span>
							<span class="title">
								<?php echo JText::_( 'COM_ICAGENDA_FORM_LBL_EVENT_IMAGE' ); ?>
							</span>
							<div class="text">
								<?php echo JText::_( 'COM_ICAGENDA_FORM_DESC_EVENT_IMAGE' ); ?>
							</div>
						</span>
						</a>
					</span>
				</div>

<!--div>
					<label><?php echo JText::_( 'ICAGENDA_REGISTRATION_FORM_EMAIL' ); ?> *</label>
					<?php if ($u_name){
						echo '<input type="email" value="'.$u_mail.'" disabled="disabled" />
						<input type="hidden" name="useremail" value="'.$u_mail.'" />';
					}else{
						echo '<input type="email" name="useremail" value="" size="30" required="true" class="required validate-email" />';
					}?>
					<span class="formInfo">
						<a class="ictip"><img src="<?php echo $infoimg; ?>" />
						<span>
							<span class="title">
								<?php echo JText::_( 'ICAGENDA_REGISTRATION_FORM_EMAIL' ); ?>
							</span>
							<div class="text">
								<?php echo JText::_( 'ICAGENDA_REGISTRATION_FORM_EMAIL_DESC' ); ?>
							</div>
						</span>
						</a>
					</span>
				</div-->


			<!--li><?php //echo $this->form->getLabel('state'); ?><?php //echo $this->form->getInput('state'); ?></li-->
			<?php //echo $this->form->getLabel('checked_out'); ?><?php //echo $this->form->getInput('checked_out'); ?>
			<?php //echo $this->form->getLabel('checked_out_time'); ?><?php //echo $this->form->getInput('checked_out_time'); ?>

			</div>
		</div>

		<div>&nbsp;</div>


		<legend><?php echo JText::_('COM_ICAGENDA_LEGEND_DATES'); ?></legend>

		<div class="fieldset">



			<div>
				<h3><?php echo JText::_('COM_ICAGENDA_LEGEND_PERIOD_DATES'); ?></h3>
				<div>

<label><?php echo JText::_( 'COM_ICAGENDA_FORM_LBL_EVENTPERIOD_START' ); ?></label>
					<input type="text" name="startdate" id="startdate">
					<span class="formInfo">
						<a class="ictip"><img src="<?php echo $infoimg; ?>" />
						<span>
							<span class="title">
								<?php echo JText::_( 'COM_ICAGENDA_FORM_LBL_EVENTPERIOD_START' ); ?>
							</span>
							<div class="text">
								<?php echo JText::_( 'COM_ICAGENDA_FORM_DESC_EVENTPERIOD_START' ); ?>
							</div>
						</span>
						</a>
					</span>
				</div>
				<div>
					<label><?php echo JText::_( 'COM_ICAGENDA_FORM_LBL_EVENTPERIOD_END' ); ?></label>
					<input type="text" name="enddate" id="enddate">
					<span class="formInfo">
						<a class="ictip"><img src="<?php echo $infoimg; ?>" />
						<span>
							<span class="title">
								<?php echo JText::_( 'COM_ICAGENDA_FORM_LBL_EVENTPERIOD_END' ); ?>
							</span>
							<div class="text">
								<?php echo JText::_( 'COM_ICAGENDA_FORM_DESC_EVENTPERIOD_END' ); ?>
							</div>
						</span>
						</a>
					</span>
				</div>

				<div class="control-group">
					<?php echo $this->form->getLabel('weekdays'); ?>
					<div class="controls">
						<?php echo $this->form->getInput('weekdays'); ?>
					</div>
				</div>

				<h3><?php echo JText::_('COM_ICAGENDA_LEGEND_SINGLE_DATES'); ?></h3>
				<div>
					<?php echo $this->form->getInput('dates'); ?>
				</div>
									<div class="control-group">
										<?php echo $this->form->getLabel('next'); ?>
										<div class="controls">
											<?php echo $this->form->getInput('next'); ?>
										</div>
									</div>
			</div>
							<?php
							echo '<fieldset style="margin:0">'
								.JHtml::_('sliders.start', 'info-slider', array('useCookie'=>0, 'startOffset'=>-1, 'startTransition'=>1))
								.JHtml::_('sliders.panel', JText::_('COM_ICAGENDA_DATES_HELP'), 'slide1')
								.'<fieldset class="panelform" >'
								.'<ul class="adminformlist" style="color:#555555;">'
								.'<div>'. JText::_('COM_ICAGENDA_DATES_HELP_INTRO').'</div><br>'
								.'<div style="text-transform:uppercase;"><b>'. JText::_('COM_ICAGENDA_LEGEND_SINGLE_DATES').'</b></div>'
								.'<div><b>&#9658; '. JText::_('COM_ICAGENDA_DATES_HELP_LINE1').'</b></div>'
								.'<div><i>'. JText::_('COM_ICAGENDA_DATES_HELP_EXAMPLE1').'</i></div><br>'
								.'<div><b>&#9658; '. JText::_('COM_ICAGENDA_DATES_HELP_LINE2').'</b></div>'
								.'<div><i>'. JText::_('COM_ICAGENDA_DATES_HELP_EXAMPLE2').'</i></div><br>'
								.'<div style="text-transform:uppercase;"><b>'. JText::_('COM_ICAGENDA_LEGEND_PERIOD_DATES').'</b></div>'
								.'<div><b>&#9658; '. JText::_('COM_ICAGENDA_DATES_HELP_LINE3').'</b></div>'
								.'<div><i>'. JText::_('COM_ICAGENDA_DATES_HELP_EXAMPLE3').'</i></div><br>'
								.'<div style="text-transform:uppercase;"><b>'. JText::_('COM_ICAGENDA_LEGEND_PERIOD_DATES').' & '. JText::_('COM_ICAGENDA_LEGEND_SINGLE_DATES').'</b></div>'
								.'<div><b>&#9658; '. JText::_('COM_ICAGENDA_DATES_HELP_LINE4').'</b></div>'
								.'<div><i>'. JText::_('COM_ICAGENDA_DATES_HELP_EXAMPLE4').'</i></div><br>'
								.'<div><b>&#9658; '. JText::_('COM_ICAGENDA_DATES_HELP_LINE5').'</b></div>'
								.'<div><i>'. JText::_('COM_ICAGENDA_DATES_HELP_EXAMPLE5').'</i></div><br>'
								.'</ul>'
								.'</fieldset>'
								.JHtml::_('sliders.end')
								.'<br />';
							?>


		</div>

		<div>&nbsp;</div>


		<legend><?php echo JText::_('COM_ICAGENDA_LEGEND_DESC'); ?></legend>

		<div class="fieldset">


			<div>
				<div>

<!--label><?php echo JText::_( 'COM_ICAGENDA_FORM_LBL_EVENT_DESC' ); ?></label>
					<span class="formInfo">
						<a class="ictip"><img src="<?php echo $infoimg; ?>" />
						<span>
							<span class="title">
								<?php echo JText::_( 'COM_ICAGENDA_FORM_LBL_EVENT_DESC' ); ?>
							</span>
							<div class="text">
								<?php echo JText::_( 'COM_ICAGENDA_FORM_DESC_EVENT_DESC' ); ?>
							</div>
						</span>
						</a>
					</span>
					<div>&nbsp;</div-->
					<?php echo $this->form->getInput('desc'); ?>
				</div>
			</div>
		</div>

		<div>&nbsp;</div>
		<div>&nbsp;</div>
		<div>&nbsp;</div>


		<legend><?php echo JText::_('COM_ICAGENDA_LEGEND_INFORMATION'); ?></legend>

		<div class="fieldset">
			<div>
				<h3><?php echo JText::_('COM_ICAGENDA_LEGEND_VENUE'); ?></h3>
				<div>

<label><?php echo JText::_( 'COM_ICAGENDA_FORM_LBL_EVENT_VENUE' ); ?></label>
					<?php echo $this->form->getInput('place'); ?>
					<span class="formInfo">
						<a class="ictip"><img src="<?php echo $infoimg; ?>" />
						<span>
							<span class="title">
								<?php echo JText::_( 'COM_ICAGENDA_FORM_LBL_EVENT_VENUE' ); ?>
							</span>
							<div class="text">
								<?php echo JText::_( 'COM_ICAGENDA_FORM_DESC_EVENT_VENUE' ); ?>
							</div>
						</span>
						</a>
					</span>
				</div>
				<h3><?php echo JText::_('COM_ICAGENDA_LEGEND_CONTACT'); ?></h3>
				<div>

<label><?php echo JText::_( 'COM_ICAGENDA_FORM_LBL_EVENT_EMAIL' ); ?></label>
					<?php echo $this->form->getInput('email'); ?>
					<span class="formInfo">
						<a class="ictip"><img src="<?php echo $infoimg; ?>" />
						<span>
							<span class="title">
								<?php echo JText::_( 'COM_ICAGENDA_FORM_LBL_EVENT_EMAIL' ); ?>
							</span>
							<div class="text">
								<?php echo JText::_( 'COM_ICAGENDA_FORM_DESC_EVENT_EMAIL' ); ?>
							</div>
						</span>
						</a>
					</span>
				</div>
				<div>

<label><?php echo JText::_( 'COM_ICAGENDA_FORM_LBL_EVENT_PHONE' ); ?></label>
					<?php echo $this->form->getInput('phone'); ?>
					<span class="formInfo">
						<a class="ictip"><img src="<?php echo $infoimg; ?>" />
						<span>
							<span class="title">
								<?php echo JText::_( 'COM_ICAGENDA_FORM_LBL_EVENT_PHONE' ); ?>
							</span>
							<div class="text">
								<?php echo JText::_( 'COM_ICAGENDA_FORM_DESC_EVENT_PHONE' ); ?>
							</div>
						</span>
						</a>
					</span>
				</div>
				<div>

<label><?php echo JText::_( 'COM_ICAGENDA_FORM_LBL_EVENT_WEBSITE' ); ?></label>
					<?php echo $this->form->getInput('website'); ?>
					<span class="formInfo">
						<a class="ictip"><img src="<?php echo $infoimg; ?>" />
						<span>
							<span class="title">
								<?php echo JText::_( 'COM_ICAGENDA_FORM_LBL_EVENT_WEBSITE' ); ?>
							</span>
							<div class="text">
								<?php echo JText::_( 'COM_ICAGENDA_FORM_DESC_EVENT_WEBSITE' ); ?>
							</div>
						</span>
						</a>
					</span>
				</div>

				<h3><?php echo JText::_('COM_ICAGENDA_LEGEND_ALLEG'); ?>
				</h3>
				<div>

<label><?php echo JText::_( 'COM_ICAGENDA_FORM_LBL_EVENT_FILE' ); ?></label>
					<?php echo $this->form->getInput('file'); ?>
					<span class="formInfo">
						<a class="ictip"><img src="<?php echo $infoimg; ?>" />
						<span>
							<span class="title">
								<?php echo JText::_( 'COM_ICAGENDA_FORM_LBL_EVENT_FILE' ); ?>
							</span>
							<div class="text">
								<?php echo JText::_( 'COM_ICAGENDA_FORM_DESC_EVENT_FILE' ); ?>
							</div>
						</span>
						</a>
					</span>
				</div>


			</div>
		</div>

		<div>&nbsp;</div>


		<legend><?php echo JText::_('COM_ICAGENDA_LEGEND_GOOGLE_MAPS'); ?></legend>

		<div class="fieldset">

				<div id="googlemap">
					<div class="row-fluid">
						<div class="span6 iCleft">

						<h3><?php echo JText::_('COM_ICAGENDA_GOOGLE_MAPS_SUBTITLE_LBL'); ?></h3>
						<div>
							<?php echo JText::_('COM_ICAGENDA_GOOGLE_MAPS_NOTE1'); ?>
							<br/>
							<?php echo JText::_('COM_ICAGENDA_GOOGLE_MAPS_NOTE2'); ?><br/>
						</div>
						<!--div class='clearfix'-->
						<div>

							<div class="icmap-input">
								<?php echo $this->form->getLabel('address'); ?>
									<?php echo $this->form->getInput('address'); ?>
							</div>
							<div class="icmap-input" style="height:40px">
									<?php echo $this->form->getInput('city'); ?>
							</div>
							<div class="icmap-input" style="height:40px">
									<?php echo $this->form->getInput('country'); ?>
							</div>
							<div class="icmap-input" style="height:40px">
									<?php echo $this->form->getInput('lat'); ?>
							</div>
							<div class="icmap-input" style="height:40px">
									<?php echo $this->form->getInput('lng'); ?>
							</div>
							<!--label>District: </label> <input id="administrative_area_level_2" disabled=disabled> <br/>
							<label>State/Province: </label> <input id="administrative_area_level_1" disabled=disabled> <br/-->
							<!--label>route: </label> <input id="route"> <br/>
							<label>Postal Code: </label> <input id="postal_code" disabled=disabled> <br/>
							<label>type: </label> <input id="type" disabled=disabled> <br/-->

						</div>
						</div>
						<div class="span6 iCleft">
				<div class='map-wrapper'>
					<h3>Map</h3>
					<label id="geo_label" for="reverseGeocode"><?php echo JText::_('COM_ICAGENDA_GOOGLE_MAPS_REVERSE'); ?></label>
					<select id="reverseGeocode">
						<option value="false" selected><?php echo JText::_('JNO'); ?></option>
						<option value="true"><?php echo JText::_('JYES'); ?></option>
					</select><br/>

					<div id="map"></div>
					<div id="legend"><?php echo JText::_('COM_ICAGENDA_GOOGLE_MAPS_LEGEND'); ?></div>
				</div>
						</div>

						<!--div class='input-positioned'>
							<label>Callback: </label>
							<textarea id='callback_result' rows="15"></textarea>
						</div-->
					</div>
				</div>


		</div>

		<div>&nbsp;</div>


		<legend><?php echo JText::_('COM_ICAGENDA_REGISTRATION_OPTIONS'); ?></legend>

		<div class="fieldset">
				<div>
					<?php foreach ($params as $name => $fieldSet) : ?>
							<?php if (isset($fieldSet->description) && trim($fieldSet->description)) : ?>
								<p class="tip"><?php echo $this->escape(JText::_($fieldSet->description));?></p>
							<?php endif; ?>
						<!--h3><?php echo $this->escape(JText::_($fieldSet->label)); ?></h3-->
						<?php

						foreach ($this->form->getFieldset($name) as $field) : ?>
							<div class="icmap-input">
								<?php echo $field->label; ?>
									<?php echo $field->input; ?>
							</div>
						<?php endforeach; ?>
					<?php endforeach; ?>
				</div>

		</div>

		<div>&nbsp;</div>

				<div class="icpanel iCleft" style="display:none">
					<h1><?php echo JText::_('JGLOBAL_FIELDSET_PUBLISHING'); ?></h1>
					<hr>
					<div class="row-fluid">
						<div class="span6 iCleft">
							<div class="control-group">
								<?php echo $this->form->getLabel('alias'); ?>
								<div class="controls">
									<?php echo $this->form->getInput('alias'); ?>
								</div>
							</div>
							<div class="control-group">
								<?php echo $this->form->getLabel('id'); ?>
								<div class="controls">
									<?php echo $this->form->getInput('id'); ?>
								</div>
							</div>
							<div class="control-group">
								<?php echo $this->form->getLabel('created_by'); ?>
								<div class="controls">
									<?php echo $this->form->getInput('created_by'); ?>
								</div>
							</div>
							<div class="control-group">
								<?php echo $this->form->getLabel('created_by_alias'); ?>
								<div class="controls">
									<?php echo $this->form->getInput('created_by_alias'); ?>
								</div>
							</div>
							<div class="control-group">
								<?php echo $this->form->getLabel('created'); ?>
								<div class="controls">
									<?php echo $this->form->getInput('created'); ?>
								</div>
							</div>
							<div class="control-group">
								<?php echo $this->form->getLabel('checked_out'); ?>
								<div class="controls">
									<?php echo $this->form->getInput('checked_out'); ?>
								</div>
							</div>
							<div class="control-group">
								<?php echo $this->form->getLabel('checked_out_time'); ?>
								<div class="controls">
									<?php echo $this->form->getInput('checked_out_time'); ?>
								</div>
							</div>
						</div>
					</div>
				</div>

		<?php

		if ($tos == 0) {
			$tokenHTML = str_replace('type="hidden"','id="formAgree" type="checkbox" checked style="display:none"',JHTML::_( 'form.token' ));
		}

		if ($tos == 1) {
			// Terms of Service

			$tokenHTML = str_replace('type="hidden"','id="formAgree" type="checkbox"',JHTML::_( 'form.token' ));

			// Get the site name
			$config = JFactory::getConfig();
			if(version_compare(JVERSION, '3.0', 'ge')) {
				$sitename = $config->get('sitename');
			} else {
				$sitename = $config->getValue('config.sitename');
			}

			// Tos Type
			$iCparams = JComponentHelper::getParams('com_icagenda');
			$tos_Type = $iCparams->get('tos_Type', '');
			$tosArticle = $iCparams->get('tosArticle', '');
			$tosContent = $iCparams->get('tosContent', '');

			$tosDEFAULT = JText::sprintf( 'COM_ICAGENDA_TOS', $sitename, $sitename);
			$tosARTICLE = 'index.php?option=com_content&view=article&id='.$tosArticle.'&tmpl=component';
			$tosCUSTOM = $tosContent;

			// Menu-item ID (fix 3.2.1.1)
//			$menu = JSite::getMenu();
			$menu = JFactory::getApplication()->getMenu();
			$menuItems = $menu->getActive();
			$menuID = $menuItems->id;


			?>
				<input type="hidden" name="menuID" value="<?php echo $menuID; ?>" />
		<div class="bgButton">
				<div>
					<b><big><?php echo JText::_( 'COM_ICAGENDA_TERMS_OF_SERVICE'); ?></big></b>
				</div>
						<?php
							if ($tos_Type == 1) {
								echo '<iframe src="'.htmlentities($tosARTICLE).'" width="98%" height="150"></iframe>';
							} elseif ($tos_Type == 2) {
								echo '<div style="padding: 25px; background:#FFF; color: #333; text-align:left">';
								echo $tosCUSTOM;
								echo '</div>';
							} else {
								echo '<div style="padding: 25px; background:#FFF; color: #333; text-align:left">';
								echo $tosDEFAULT;
								echo '</div>';
							}
						?>
						<!--iframe src="<?php echo htmlentities($tosURL); ?>" width="98%" height="150"></iframe-->
				<div class="agreeToS">
						<p><?php echo $tokenHTML; ?> <?php echo JText::_( 'COM_ICAGENDA_TERMS_OF_SERVICE_AGREE'); ?> *</p>
				</div>
<?php
		} else {
?>
			<?php echo $tokenHTML; ?>
			<div class="bgButton">
<?php
		}

?>

				<input type="submit" value="<?php echo JText::_( 'COM_ICAGENDA_EVENT_FORM_SUBMIT' ); ?>" class="button" name="Submit"/>
				<input type="hidden" name="return" value="index.php" />

				<?php if (false) echo JHtml::_( 'form.token' ); ?>
			<!--span class="buttonx">
				<a href="javascript:history.go(-1)" title="<?php echo JTEXT::_('COM_ICAGENDA_CANCEL'); ?>">
					<?php echo JTEXT::_('COM_ICAGENDA_CANCEL'); ?>
				</a>
			</span-->
		</div>
	</form>
</div>
<script type="text/javascript">
	//<![CDATA[

	jQuery(function($) {
			var addresspicker = $( "#addresspicker" ).addresspicker();
			var addresspickerMap = $( '#address' ).addresspicker({
				regionBias: "fr",
				updateCallback: showCallback,
				mapOptions: {
					zoom: <?php echo $zoom; ?>,
					center: new google.maps.LatLng(<?php echo $coords; ?>),
					scrollwheel: false,
					mapTypeId: google.maps.MapTypeId.<?php echo $mapTypeId; ?>,
					streetViewControl: false
				},
				elements: {
					map:      "#map",
					lat:      "#lat",
					lng:      "#lng",
					street_number: '#street_number',
					route: '#route',
					locality: '#locality',
					administrative_area_level_2: '#administrative_area_level_2',
					administrative_area_level_1: '#administrative_area_level_1',
					country:  '#country',
					postal_code: '#postal_code',
					type:    '#type',
				}
			});

			var gmarker = addresspickerMap.addresspicker( "marker");
			gmarker.setVisible(true);
			addresspickerMap.addresspicker( "updatePosition");

			$('#reverseGeocode').change(function(){
				$("#address").addresspicker("option", "reverseGeocode", ($(this).val() === 'true'));
			});

			function showCallback(geocodeResult, parsedGeocodeResult){
				$('#callback_result').text(JSON.stringify(parsedGeocodeResult, null, 4));
			}
  	});
	//]]>
</script>

<?php
//		JHtml::_('behavior.keepalive');

	$document	= JFactory::getDocument();
	$document->addStyleSheet( JURI::base( true ).'/components/com_icagenda/add/css/style.css' );

	if(file_exists("components/com_icagenda/themes/packs/".$this->template."/css/".$this->template."_component.css")){
		$document->addStyleSheet( JURI::base( true ).'/components/com_icagenda/themes/packs/'.$this->template.'/css/'.$this->template.'_component.css' );
	}else{
		$document->addStyleSheet( JURI::base( true ).'/components/com_icagenda/themes/packs/default/css/default_component.css' );
	}
	require_once $this->submit;
	if(version_compare(JVERSION, '3.0', 'lt')) {

		jimport( 'joomla.environment.request' );

		JHTML::_('stylesheet', 'icagenda.css', 'administrator/components/com_icagenda/add/css/');
		JHTML::_('stylesheet', 'jquery-ui-1.8.17.custom.css', 'administrator/components/com_icagenda/add/css/');
		JHTML::_('stylesheet', 'icmap.css', 'administrator/components/com_icagenda/add/css/');
		JHTML::_('stylesheet', 'template.css', 'administrator/components/com_icagenda/add/css/');
		JHTML::_('stylesheet', 'icagenda.j25.css', 'administrator/components/com_icagenda/add/css/');

		JHTML::_('behavior.framework');


		// load jQuery, if not loaded before (NEW VERSION IN 1.2.6)
		$scripts = array_keys($document->_scripts);
		$scriptFound = false;
		$scriptuiFound = false;
		$mapsgooglescriptFound = false;
		for ($i = 0; $i < count($scripts); $i++) {
		    if (stripos($scripts[$i], 'jquery.min.js') !== false) {
				$scriptFound = true;
		    }
			// load jQuery, if not loaded before as jquery - added in 1.2.7
			if (stripos($scripts[$i], 'jquery.js') !== false) {
				$scriptFound = true;
			}
			if (stripos($scripts[$i], 'jquery-ui.min.js') !== false) {
				$scriptuiFound = true;
			}
			if (stripos($scripts[$i], 'maps.google') !== false) {
				$mapsgooglescriptFound = true;
			}
		}

			// jQuery Library Loader
			if (!$scriptFound) {
				// load jQuery, if not loaded before
				if (!JFactory::getApplication()->get('jquery')) {
					JFactory::getApplication()->set('jquery', true);
					// add jQuery
				    $document->addScript('https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js');
		    		$document->addScript('components/com_icagenda/js/jquery.noconflict.js');
				}
			}
			// Google Maps api V3
			$document->addScript('https://maps.googleapis.com/maps/api/js?sensor=false');

			if (!$scriptuiFound) {
		    	$document->addScript('https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js');
			}

			JHTML::script('icmap.js', 'components/com_icagenda/add/js/');
			JHTML::script('icdates.js', 'components/com_icagenda/add/js/');
			JHTML::script('timepicker.js', 'components/com_icagenda/add/js/');
			JHTML::script('template.js', 'components/com_icagenda/add/js/');

		} else {

			jimport( 'joomla.environment.request' );

			JHtml::_('formbehavior.chosen', 'select');
			jimport('joomla.html.html.bootstrap');

//			$document->addStyleSheet( JURI::base().'administrator/components/com_icagenda/add/css/icagenda.css' );
			$document->addStyleSheet(  JURI::base( true ).'/components/com_icagenda/add/css/icagenda.css' );
			$document->addStyleSheet(  JURI::base( true ).'/components/com_icagenda/add/css/jquery-ui-1.8.17.custom.css' );
			$document->addStyleSheet(  JURI::base( true ).'/components/com_icagenda/add/css/icmap.css' );

			JHtml::_('bootstrap.framework');
			JHtml::_('jquery.framework');

			// Google Maps api V3
			$document->addScript('https://maps.googleapis.com/maps/api/js?sensor=false');

// Change jQuery UI version from 1.9.2 to 1.8.23 (joomla version, but not complete) to prevent a conflict in tooltip that appeared since Joomla 3.1.4
//			$document->addScript('https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js');
			$document->addScript( 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js' );

        	$document->addScript(  JURI::base( true ).'/components/com_icagenda/add/js/icmap.js' );
			$document->addScript(  JURI::base( true ).'/components/com_icagenda/add/js/icdates.js' );
			$document->addScript(  JURI::base( true ).'/components/com_icagenda/add/js/timepicker.js' );
		}

?>
