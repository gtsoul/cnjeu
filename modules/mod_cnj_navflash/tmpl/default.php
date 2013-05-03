<head>

<!--script type="text/javascript" src="swfobject.js"></script-->
		<script type="text/javascript">

		var playerVersion = swfobject.getFlashPlayerVersion(); // returns a JavaScript object
		var flashVersion =  playerVersion.major + "." + playerVersion.minor + "." + playerVersion.release;

		</script>

</head>

<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_custom
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>

<div id= "bigJPG">
  <div id="navflashwrapper">
    <a href="javascript:void(0);" class="toggle-expand">
      <img src="templates/lch_cnj/images/toggle-expand.png" class="expand" alt="expand" />
      <img src="templates/lch_cnj/images/toggle-collapse.png" class="collapse" alt="collapse" />
    </a>  
    <img src="templates/lch_cnj/images/banniere_cnj_reduite.png" alt="cnj" class="navflashalt"/>  
    <div id="navflash">
      <map name="nav_map">
        <area shape="rect" coords="45,106,396,357" href="<?php echo JURI::base(); ?>index.php?option=com_content&view=article&id=5&Itemid=114">
        <area shape="rect" coords="582,224,816,435" href="<?php echo JURI::base(); ?>index.php?option=com_content&view=article&id=15&Itemid=124">
        <area shape="rect" coords="745,27,1013,224" href="<?php echo JURI::base(); ?>index.php?option=com_content&view=article&id=22&Itemid=131">
        <area shape="rect" coords="823,312,1099,485" href="<?php echo JURI::base(); ?>index.php?option=com_content&view=article&id=28&Itemid=137">
      </map>
      <img usemap="#nav_map" src="templates/lch_cnj/images/flash-alt.jpg" alt="" />  
    </div>    
    
  </div>
</div>
<?php
/*
 * lien_ludotheque
 * lien_conservatoire
 * lien_tremplin
 * lien_observatoire
 */
?>


<div style="width:100%;text-align:center">
<script>

$(document).ready(function() {

  var flashvars = {
      lien_ludotheque: "<?php echo JURI::base(); ?>ludotheque.php",
      lien_conservatoire: "<?php echo JURI::base(); ?>conservatoire.php",
      lien_tremplin: "<?php echo JURI::base(); ?>tremplin.php",
      lien_observatoire: "<?php echo JURI::base(); ?>observatoire.php"
  };      
  var params = {
      menu: "false",
      wmode: "transparent"
  };
  var attributes = { };
 
  $('#navflashwrapper .toggle-expand').click(function() {
    if($('#navflashwrapper').hasClass('expanded')) {
      $('#navflashwrapper').removeClass('expanded');
    } else {
      $('#navflashwrapper').addClass('expanded');
      if (flashVersion != "0.0.0" && $('#navflash map').length > 0)  {
        swfobject.embedSWF('<?php echo JURI::base(); ?>templates/lch_cnj/images/nav_HP.swf',
                  'navflash', '1200', '485', '10', 'expressInstall.swf', flashvars, params, attributes);
      }
    } 
  });
});



</script> 
</div>

