<?php
/**
 *------------------------------------------------------------------------------
 *  iCagenda v3 by Jooml!C - Events Management Extension for Joomla! 2.5 / 3.x
 *------------------------------------------------------------------------------
 * @package     com_icagenda - mod_iccalendar
 * @copyright   Copyright (c)2012-2013 Cyril Rezé, Jooml!C - All rights reserved
 *
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Cyril Rezé (Lyr!C)
 * @link        http://www.joomlic.com
 *
 * @version     3.1.9 2013-09-04
 * @since       1.0
 *------------------------------------------------------------------------------
*/

/**
 *	iCagenda - iC calendar
 */


// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// $com_front = dirname(JApplicationHelper::getPath('front','com_icagenda'));

// Test if translation is missing, set to en-GB by default
$language= JFactory::getLanguage();
$language->load( 'mod_iccalendar', JPATH_SITE.'/modules/mod_iccalendar', 'en-GB', true );
$language->load( 'mod_iccalendar', JPATH_SITE.'/modules/mod_iccalendar', null, true );

// Load file helper
//if(!class_exists('modiCcalendarHelper'))require_once ($com_front.'/helpers/icmodcalendar.php');
//if(!class_exists('modiCcalendarHelper'))require_once (JPATH_BASE.'/components/com_icagenda/helpers/icmodcalendar.php');

// Include the class of the syndicate functions only once
if(!class_exists('modiCcalendarHelper'))require_once(dirname(__FILE__).'/helper.php');

jimport( 'joomla.environment.request' );

// Module ID
$modid=$module->id;

// Module
$cal=new modiCcalendarHelper;
$data = $cal->getStamp($params);
$date_start=date('Y-m-d');

//$modid=JRequest::getVar('id');
$dateget=JRequest::getVar('date');
if(isset($dateget)) $date_start=$dateget;
$nav=$cal->getNav($date_start, $modid);

// Params of the Module iC Calendar

//$template=$params->get('template');
$mouseover=$params->get('mouseover');
$position=$params->get('position');
$posmiddle=$params->get('posmiddle');
$moduleclass_sfx=$params->get('moduleclass_sfx');
$mon=$params->get('mon');
$tue=$params->get('tue');
$wed=$params->get('wed');
$thu=$params->get('thu');
$fri=$params->get('fri');
$sat=$params->get('sat');
$sun=$params->get('sun');
$firstday=$params->get('firstday');
$bgcolor=$params->get('bgcolor');
$bgimage=$params->get('bgimage');
$bgimagerepeat=$params->get('bgimagerepeat');

if ($firstday == NULL) {
	$firstday = '1';
}
if ($firstday == '0') {
	$na=7;$nb=1;$nc=2;$nd=3;$ne=4;$nf=5;$ng=6;
}

if ($firstday == '1') {
	$na=1;$nb=2;$nc=3;$nd=4;$ne=5;$nf=6;$ng=7;
}


$document	= JFactory::getDocument();

$template = $params->get('template', 'default');

// Search template of iC Calendar
if(!file_exists(JPATH_ROOT.'/components/com_icagenda/themes/packs/'.$template.'/'.$template.'_calendar.php')){
	$template='default';
}
$t_calendar = JPATH_BASE.'/components/com_icagenda/themes/packs/'.$template.'/'.$template.'_calendar.php';
$t_day = JPATH_BASE.'/components/com_icagenda/themes/packs/'.$template.'/'.$template.'_day.php';



if(version_compare(JVERSION, '3.0', 'lt')) {

	// Load Theme Pack css
	JHTML::_('stylesheet', $template.'_module.css', 'components/com_icagenda/themes/packs/'.$template.'/css/');

	//Load JS
	JHTML::_('behavior.mootools');

} else {

	// Load Theme Pack css
	$document->addStyleSheet( 'components/com_icagenda/themes/packs/'.$template.'/css/'.$template.'_module.css' );

}

$document->addStyleSheet( 'media/com_icagenda/icicons/style.css' );

// load jQuery, if not loaded before
$header = $document->getHeadData();
$loadJquery = true;
switch($params->get('loadJquery',"auto"))
	{

	case "0":
			$loadJquery = false;
			break;
	case "1":
			$loadJquery = true;
			break;
	case "auto":
			foreach($header['scripts'] as $scriptName => $scriptData)
			{
				if(substr_count($scriptName,'jquery'))
				{
					$loadJquery = false;
					break;
				}
			}
			break;
	}

//Add js
if($loadJquery) {
	$document->addScript( 'https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js' );
}
$document->addScript( 'modules/mod_iccalendar/js/jquery.noconflict.js' );
//$document->addScript( 'modules/mod_iccalendar/js/function.js' );
//$document->addScript( 'modules/mod_iccalendar/js/ictip.js' );




// control options tip position center
if ($position == 'center') {
	$posit=$posmiddle.$position;
}

if ($position != 'center') {
	$posit=$position;
}

$icclasstip = '.icevent a';
$icclass = '.iccalendar';
$icagendabtn = '.icagendabtn_'.$modid;
$mod_iccalendar = '#mod_iccalendar_'.$modid;

//Set Translation Strings
JText::script('MOD_ICCALENDAR_LOADING');

// Build the script.
/*
$script = array();

$script[] = '	';
$script[] = '	jQuery(document).ready(function($){';
$script[] = '		var icmouse = "'.$mouseover.'";';
$script[] = '		var icclasstip = "'.$icclasstip.'";';
$script[] = '		var icclass = "'.$icclass.'";';
$script[] = '		var posit = "'.$posit.'";';
$script[] = '		var modid = "'.$modid.'";';
//$script[] = '		var modidid = #''.$modid.'';';
$script[] = '		var icagendabtn = "'.$icagendabtn.'";';
$script[] = '		var mod_iccalendar = "'.$mod_iccalendar.'";';
//$script[] = '		var template = .''.$template.'';';
$script[] = '	';
$script[] = '	';
$script[] = '	';
$script[] = '	';
$script[] = '		jQuery(document).on("click", icagendabtn, function(e){';
$script[] = '			e.preventDefault();';
$script[] = '			url=$(this).attr("href");';
$script[] = '			$(mod_iccalendar).html("<div class="icloading_box"><div class="icloading_img"></div><div>"+Joomla.JText._("MOD_ICCALENDAR_LOADING", "loading...")+"<div></div>").load(url+" "+mod_iccalendar);';
$script[] = '		});';
$script[] = '		jQuery(document).on(icmouse, modidid+" "+icclasstip, function(e){';
$script[] = '			e.preventDefault();';
$script[] = '			$("#ictip").remove();';
$script[] = '			$parent=$(this).parent();';
$script[] = '			$tip=$($parent).children(modidid+" .spanEv").html();';

						// Tip Position: Left
$script[] = '			if (posit=="left") {';
$script[] = '				$width="390px";';
$script[] = '				$pos=$(modidid).offset().left -450+"px";';
$script[] = '				$top=$(modidid).offset().top -45+"px";';
$script[] = '			}';
						// Tip Position: Right
$script[] = '			if (posit=="right") {';
$script[] = '				$width="390px";';
$script[] = '				$pos=$(modidid).offset().left+$(modidid).width()+10+"px";';
$script[] = '				$top=$(modidid).offset().top -45+"px";';
$script[] = '			}';
						// Tip Position: Center
$script[] = '			if (posit=="center") {';
$script[] = '				$width="25%";';
$script[] = '				$pos="36%";';
$script[] = '				$top=$(modidid).offset().top-$(modidid).height()+0+"px";';
$script[] = '			}';
						// Tip Position: Top Center
$script[] = '			if (posit=="topcenter") {';
$script[] = '				$width="25%";';
$script[] = '				$pos="36%";';
$script[] = '				$top=$(modidid).offset().top-$(modidid).height()+0+"px";';
$script[] = '			}';
						// Tip Position: Bottom Center
$script[] = '			if (posit=="bottomcenter") {';
$script[] = '				$width="25%";';
$script[] = '				$pos="36%";';
$script[] = '				$top=$(modidid).offset().top+$(modidid).height()+0+"px";';
$script[] = '			}';
						// ToolTip Body
$script[] = '			$("body").append("<div style="display:block; position:absolute; width:"+$width+"; left:"+$pos+"; top:"+$top+";" id="ictip"> "+$(this).parent().children(".date").html()+"<a class="close" style="cursor: pointer;"><div style="display:block; width:30px; height:50px; text-align:center;">X</div></a><br /><br /><span class="clr"></span>"+$tip+"</div>");';
						// Close ToolTip
$script[] = '			$(document).on("click", ".close", function(e){';
$script[] = '				e.preventDefault();';
$script[] = '				$("#ictip").remove();';
$script[] = '			});';
$script[] = '		});';
$script[] = '	});';

// Add the script to the document head.
JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));
*/

?>

<script type="text/javascript">
(function($){
	var icmouse = '<?php echo $mouseover; ?>';
	var icclasstip = '<?php echo $icclasstip; ?>';
	var icclass = '<?php echo $icclass; ?>';
	var posit = '<?php echo $posit; ?>';
	var modid = '<?php echo $modid; ?>';
	var modidid = '<?php echo '#'.$modid; ?>';
	var icagendabtn = '<?php echo $icagendabtn; ?>';
	var mod_iccalendar = '<?php echo $mod_iccalendar; ?>';
	var template = '<?php echo '.'.$template; ?>';

	$(document).on('click', icagendabtn, function(e){
		e.preventDefault();

		url=$(this).attr('href');

		$(mod_iccalendar).html('<div class="icloading_box"><div class="icloading_img"><\/div><div>'+Joomla.JText._('MOD_ICCALENDAR_LOADING', 'loading...')+'<div><\/div>').load(url+' '+mod_iccalendar);

	});
	$(document).on(icmouse, modidid+' '+icclasstip, function(e){
		e.preventDefault();
		$('#ictip').remove();
		$parent=$(this).parent();
		$tip=$($parent).children(modidid+' .spanEv').html();

		//Left
		if (posit=='left') {
			$width='390px';
			$pos=$(modidid).offset().left -450+'px';
			$top=$(modidid).offset().top -45+'px';
		}
		//Right
		if (posit=='right') {
			$width='390px';
			$pos=$(modidid).offset().left+$(modidid).width()+10+'px';
			$top=$(modidid).offset().top -45+'px';
		}
		//Center
		if (posit=='center') {
			$width='25%';
			$pos='36%';
			$top=$(modidid).offset().top-$(modidid).height()+0+'px';
		}
		//Top Center
		if (posit=='topcenter') {
			$width='25%';
			$pos='36%';
			$top=$(modidid).offset().top-$(modidid).height()+0+'px';
		}
		//Bottom Center
		if (posit=='bottomcenter') {
			$width='25%';
			$pos='36%';
			$top=$(modidid).offset().top+$(modidid).height()+0+'px';
		}


		$('body').append('<div style="display:block; position:absolute; width:'+$width+'; left:'+$pos+'; top:'+$top+';" id="ictip"> '+$(this).parent().children('.date').html()+'<a class="close" style="cursor: pointer;"><div style="display:block; width:30px; height:50px; text-align:center;">X<\/div></a><br /><br /><span class="clr"></span>'+$tip+'<\/div>');
		$(document).on('click', '.close', function(e){
			e.preventDefault();
			$('#ictip').remove();
		});
	});

}) (jQuery);
</script>

<?php

unset($stamp);

//if (!class_exists($stamp)) {
$stamp = new cal($data, $t_calendar, $t_day, $nav, $mon, $tue, $wed, $thu, $fri, $sat, $sun, $firstday, $bgcolor, $bgimage, $bgimagerepeat, $na, $nb, $nc, $nd, $ne, $nf, $ng, $moduleclass_sfx, $modid, $template);
//}
// else {
//}


require $t_calendar;
