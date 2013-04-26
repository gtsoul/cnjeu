<?php 
/**
* @package   iNow Slider
* @copyright Copyright (C) 2009 - 2010 Open Source Matters. All rights reserved.
* @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php
* Contact to : info@inowweb.com, inowweb.com
**/



defined('_JEXEC') or die('Restricted access'); 


ini_set('display_errors',0);
$path=$_SERVER['HTTP_HOST'].$_SERVER[REQUEST_URI];
$doc =& JFactory::getDocument();
$show_jquery=$params->get('show_jquery');
$load=$params->get('load');
$jver=$params->get('jver');







$doc->addStyleSheet ( 'modules/mod_iNowSlider/css/diapo.css' );



if($show_jquery=="yes" && $load=="onload" && $jver=="1.6.1")







{

$doc->addScript("modules/mod_iNowSlider/js/jquery-1.6.1.min.js");



}







else if ($show_jquery=="yes" && $load=="onload" && $jver!="1.6.1")



{



$doc->addScript("http://ajax.googleapis.com/ajax/libs/jquery/".$jver."/jquery.min.js");



}











$uri 		=& JFactory::getURI();

$url= $uri->root();

$moduleid=$module->id;



$slidewidth 			= 	$params->get( 'slidewidth');

$slideheight		= 	$params->get( 'slideheight');

$imageeffect	= 	$params->get( "menu_style");

$easing	= 	$params->get( "easing");

$navigation		= 	$params->get( 'navigation');

$dotspostop		= 	$params->get( 'dotspostop');

$dotsposleft		= 	$params->get( 'dotsposleft');



$dotsstyle		= 	$params->get( 'dotsstyle');

$arrowsstyle		= 	$params->get( 'arrowsstyle');





$manual		= 	$params->get( 'manual');

$commands		= 	$params->get( 'commands');

$pauseOnClick		= 	$params->get( 'pauseOnClick');

$timeinterval		= 	$params->get( 'timeinterval');

$velocity		= 	$params->get( 'velocity');

$linktarget		= 	$params->get( 'linktarget');

$linkedtitle		= 	$params->get( 'linkedtitle');

$border		= 	$params->get( 'border');

$bordercolor		= 	$params->get( 'bordercolor');

$borderrounded		= 	$params->get( 'borderrounded');

$shadow		= 	$params->get( 'shadow');

$loader	= 	$params->get( 'loader');

$loaderOpacity	= 	$params->get( 'loaderOpacity');

$loaderColor	= 	$params->get( 'loaderColor');

$loaderBgColor	= 	$params->get( 'loaderBgColor');

$pieDiameter	= 	$params->get( 'pieDiameter');

$piePositiontop	= 	$params->get( 'piePositiontop');

$piePositionright	= 	$params->get( 'piePositionright');

$piepositionh	= 	$params->get( 'piepositionh');

$piepositionv	= 	$params->get( 'piepositionv');





$pieStroke	= 	$params->get( 'pieStroke');

$barPosition	= 	$params->get( 'barPosition');

$barStroke	= 	$params->get( 'barStroke');



$arrows=$params->get('arrows');

$hidetools=$params->get('hidetools');

$navigation=$params->get('navigation');

$backgroundcolor		= 	$params->get( 'backgroundcolor');

$align		= 	$params->get( 'align');

$dotspos=$params->get('dotspos');

$dotsstyle=$params->get('dotsstyle');

//$arrowspos=$params->get('arrowspos');

$arrowsstyle=$params->get('arrowsstyle');

$labelcolor		= 	$params->get( 'labelcolor');

$desccolor		= 	$params->get( 'desccolor');

$labelsize		= 	$params->get( 'labelsize');

$descsize		= 	$params->get( 'descsize');

$titlefont		= 	$params->get( 'titlefont');

$descfont		= 	$params->get( 'descfont');

$captionbg		= 	$params->get( 'captionbg');

$captionob		= 	$params->get( 'captionob');

$captionpos		= 	$params->get( 'captionpos');

$captioneffect		= 	$params->get( 'captioneffect');





$captionwidth	= 	$params->get( 'captionwidth');

$captionh	= 	$params->get( 'captionh');

$captionh2	= 	$params->get( 'captionh2');

$hspace	= 	$params->get( 'hspace');

$vspace	= 	$params->get( 'vspace');

$cfromv	= 	$params->get( 'cfromv');

$cfromh	= 	$params->get( 'cfromh');

$crounded	= 	$params->get( 'crounded');

if($descfont=="arial")

{

$descfont='Arial, Helvetica, sans-serif';

}





if($titlefont=="arial")



{







$titlefont='Arial, Helvetica, sans-serif';







}







if($descfont=="tnr")







{







$descfont='"Times New Roman", Times, serif';







}







if($titlefont=="tnr")







{







$titlefont='"Times New Roman", Times, serif';







}







if($descfont=="cn")







{







$descfont='"Courier New", Courier, monospace';







}







if($titlefont=="cn")







{







$titlefont='"Courier New", Courier, monospace';







}







if($descfont=="georgia")







{







$descfont='Georgia, "Times New Roman", Times, serif';







}







if($titlefont=="georgia")







{







$titlefont='Georgia, "Times New Roman", Times, serif';







}







if($descfont=="verdana")







{







$descfont='Verdana, Arial, Helvetica, sans-serif';







}







if($descfont=="verdana")







{







$titlefont='Verdana, Arial, Helvetica, sans-serif';







}



















if($manual=="yes")

{

$manual="false";

}

else

{$manual="true";}





if($commands=="yes")

{

$commands="true";

}

else

{$commands="false";}



if($pauseOnClick=="yes")

{

$pauseOnClick="true";

}

else

{$pauseOnClick="false";}







if($navigation=="yes")







{







$navigation="true";







}







else







{$navigation="false";}



















if($arrows=="yes")







{







$arrows="true";







}







else







{$arrows="false";}























if($hidetools=="yes")







{







$hidetools="true";







}







else







{$hidetools="false";}



















$img1=$params->get('img1');







$img2=$params->get('img2');







$img3=$params->get('img3');







$img4=$params->get('img4');







$img5=$params->get('img5');







$img6=$params->get('img6');







$img7=$params->get('img7');







$img8=$params->get('img8');







$img9=$params->get('img9');







$img10=$params->get('img10');



$img11=$params->get('img11');







$img12=$params->get('img12');







$img13=$params->get('img13');







$img14=$params->get('img14');







$img15=$params->get('img15');







$img16=$params->get('img16');







$img17=$params->get('img17');







$img18=$params->get('img18');







$img19=$params->get('img19');







$img20=$params->get('img20');







$label1=$params->get('label1');







$label2=$params->get('label2');







$label3=$params->get( 'label3');







$label4=$params->get('label4');







$label5=$params->get('label5');







$label6=$params->get( 'label6');







$label7=$params->get('label7');







$label8=$params->get('label8');







$label9=$params->get( 'label9');







$label10=$params->get('label10');



$label11=$params->get('label11');







$label12=$params->get('label12');







$label13=$params->get( 'label13');







$label14=$params->get('label14');







$label15=$params->get('label15');







$label16=$params->get( 'label16');







$label17=$params->get('label17');







$label18=$params->get('label18');







$label19=$params->get( 'label19');







$label20=$params->get('label20');







$desc1=$params->get('desc1');







$desc2=$params->get('desc2');







$desc3=$params->get('desc3');







$desc4=$params->get('desc4');







$desc5=$params->get('desc5');







$desc6=$params->get('desc6');







$desc7=$params->get('desc7');







$desc8=$params->get('desc8');







$desc9=$params->get('desc9');







$desc10=$params->get('desc10');



$desc11=$params->get('desc11');







$desc12=$params->get('desc12');







$desc13=$params->get('desc13');







$desc14=$params->get('desc14');







$desc15=$params->get('desc15');







$desc16=$params->get('desc16');







$desc17=$params->get('desc17');







$desc18=$params->get('desc18');







$desc19=$params->get('desc19');







$desc20=$params->get('desc20');







$link1=$params->get( 'link1');







$link2=$params->get( 'link2');







$link3=$params->get( 'link3');







$link4=$params->get( 'link4');







$link5=$params->get( 'link5');







$link6=$params->get( 'link6');







$link7=$params->get( 'link7');







$link8=$params->get( 'link8');







$link9=$params->get( 'link9');







$link10=$params->get( 'link10');



$link11=$params->get( 'link11');







$link12=$params->get( 'link12');







$link13=$params->get( 'link13');







$link14=$params->get( 'link14');







$link15=$params->get( 'link15');







$link16=$params->get( 'link16');







$link17=$params->get( 'link17');







$link18=$params->get( 'link18');







$link19=$params->get( 'link19');







$link20=$params->get( 'link20');















/***********************************LABELS **********************************************/



$img=array($img1,$img2,$img3,$img4,$img5,$img6,$img7,$img8,$img9,$img10,$img11,$img12,$img13,$img14,$img15,$img16,$img17,$img18,$img19,$img20);



$labels=array($label1,$label2,$label3,$label4,$label5,$label6,$label7,$label8,$label9,$label10,$label11,$label12,$label13,$label14,$label15,$label16,$label17,$label18,$label19,$label20);







$descs=array($desc1,$desc2,$desc3,$desc4,$desc5,$desc6,$desc7,$desc8,$desc9,$desc10,$desc11,$desc12,$desc13,$desc14,$desc15,$desc16,$desc17,$desc18,$desc19,$desc20);



$links=array($link1,$link2,$link3,$link4,$link5,$link6,$link7,$link8,$link9,$link10,$link11,$link12,$link13,$link14,$link15,$link16,$link17,$link18,$link19,$link20);











$javascript="

  var ins".$moduleid." = jQuery.noConflict();



     ins".$moduleid."(window).load(function() {

	ins".$moduleid."('.pix_diapo".$moduleid."').diapo({

selector			: 'div',

		fx					: '".$imageeffect."',



		mobileFx			: '',	

		slideOn				: 'random',	

				

		gridDifference		: 250,	

		

		easing				: '".$easing."',	

		

		mobileEasing		: '',	

		

		loader				: '". $loader."',

		

		loaderOpacity		: ". $loaderOpacity.",	

		

		loaderColor			: '". $loaderColor."', 

		

		loaderBgColor		: '". $loaderBgColor."', 

		

		pieDiameter			: ". $pieDiameter.",

		

		piePosition			: '". $piepositionv.":". $piePositiontop."px; ". $piepositionh.":". $piePositionright."px',	

		

		pieStroke			: ". $pieStroke.",

		

		barPosition			: '". $barPosition."',	

		

		barStroke			: ". $barStroke.",

		

		navigation			: ". $arrows.",	

		

		mobileNavigation	: true,	

		

		navigationHover		: ". $hidetools.",	

		

		mobileNavHover		: true,

		

		commands			: ". $commands.",

		

		mobileCommands		: true,	

				

		pagination			: ". $navigation.", 

	

		

		mobilePagination	: true,	

		

		thumbs				: false,	

		hover				: false,

		pauseOnClick		: false,

		rows				: 4,

		cols				: 6,

		slicedRows			: 8,	

		slicedCols			: 12,	

		time				: ". $timeinterval.",	

		transPeriod			: ". $velocity.",	

		autoAdvance			: ". $manual.",	

		mobileAutoAdvance	: true, 

		onStartLoading		: function() {  },

		

		onLoaded			: function() {  },

		

		onEnterSlide		: function() {  },

		

		onStartTransition	: function() {  }

	});

});



";











if($load=="onload")







{







$doc->addScriptDeclaration($javascript);







}















$count=0;



for($i=0;$i<20;$i++)



{



if($descs[$i]!="")



{



$descs[$i]='<p>'.$descs[$i].'</p>';



}







if($labels[$i]=="")







{$labels[$i]='';}







else



{



if($linkedtitle=="no" || $links[$i]=="")



{



$labels[$i]='<div class="caption elemHover '.$captioneffect.'">







                <h5>'.$labels[$i].'</h5>'.$descs[$i].'





            </div>';



}



if($linkedtitle=="yes" && $links[$i]!="")



{



$labels[$i]='<div class="caption elemHover '.$captioneffect.'">







                <h5><a href="'.$links[$i].'" target="'.$linktarget.'">'.$labels[$i].'</a></h5>'.$descs[$i].'



            </div>';



}





			}// end else

			

if($labels[$i]!="")

			{

$labels[$i]=$labels[$i].'</div>';

}









if($img[$i]=="")







{







$image[$i]="";







}	







else







{







$image[$i]='<img src="'.$img[$i].'" alt="" width="'.$slidewidth.'px" height="'.$slideheight.'px" />';







if($labels[$i]!="")



{



$image[$i]='<img src="'.$img[$i].'" alt=""  width="'.$slidewidth.'px" height="'.$slideheight.'px" />';



}







if($links[$i]!="")



{



$image[$i]='<a href="'.$links[$i].'" target="'.$linktarget.'">'.$image[$i].'</a>';







}

$image[$i]='<div data-thumb="'.$img[$i].'">'.$image[$i];



$count++;



			if($labels[$i]=="")

			{

$image[$i]=$image[$i].'</div>';

}



}







}//end for



 ?>




<style type="text/css">

.pix_diapo<?php echo $moduleid;?> .caption h5



{



padding-left: 5px !important;



}







 .pix_diapo<?php echo $moduleid;?> .caption h5, .pix_diapo<?php echo $moduleid;?>  .caption h5 a{



margin:0 !important;



<?php if($titlefont!="default")



{ ?>



font-family: <?php echo $titlefont;?> !important;



<?php } ?>



font-size:<?php echo $labelsize;?>px !important;



font-weight:normal !important; 



text-decoration:none !important;



padding-right: 5px !important;



padding-bottom:0px !important;



padding-top:1px !important;



color:<?php echo $labelcolor;?> !important;



line-height:<?php echo $labelsize+5;?>px !important;



display: block !important;



text-align:left !important;







}



.pix_diapo<?php echo $moduleid;?> .caption p{







letter-spacing: 0.4px !important;







line-height:<?php echo $descsize+5;?>px !important;







margin:0 !important;







<?php if($descfont!="default")







{ ?>







font-family: <?php echo $descfont;?> !important;







<?php } ?>







font-size:<?php echo $descsize;?>px !important;



padding-left: 5px !important;



padding-right: 5px !important;



padding-bottom:2px !important;



padding-top:0px !important;



color:<?php echo $desccolor;?> !important;



z-index:10 !important;



display: block !important;



text-align:left !important;







}













.pix_diapo<?php echo $moduleid;?> {

	background: <?php echo $backgroundcolor;?>;

	border:<?php echo $bordercolor;?> solid <?php echo $border;?>px;

	<?php if($borderrounded=="yes"){ ?>

    -moz-border-radius: 8px 8px 8px 8px;

    -webkit-border-radius: 8px 8px 8px 8px;

    border-radius: 8px 8px 8px 8px;

	<?php }?>

	<?php if($shadow=="yes") { ?>

	-moz-box-shadow: 0 3px 6px #000;

	-webkit-box-shadow: 0 3px 6px #000;

	box-shadow: 0 3px 6px #000;

		<?php if($commands=="false" && $navigation=="false") { ?>

	margin-bottom:10px;

		<?php }?>



	<?php }?>

	height: <?php echo $slideheight;?>px;

	margin-top:0px;

	margin-right:10px;

	overflow: hidden;

	position: relative;

	width: <?php echo $slidewidth;?>px;

}

#pix_pag {

	/*margin: 0 auto;*/

	position: relative;

	width: <?php echo $slidewidth;?>px;

	z-index: 1002;

}







	<?php

	if($captionpos=="bottom")

	{ ?>

.pix_diapo<?php echo $moduleid;?> .caption {

	position:absolute;

	left:0px;

	bottom:0px !important;

	background:<?php echo $captionbg;?>;

	-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=<?php echo $captionob*100;?>)";

       filter: alpha(<?php echo $captionob*100;?>);

	opacity:<?php echo $captionob;?>; /* Overridden by captionOpacity setting */

	width:100%;

			<?php if($captionh=="full")

	{?>

	height:100%;

	<?php }?>

	

	<?php if($captionh=="custom")

	{?>

	height:<?php echo $captionh2;?>px;

	<?php }?>

	z-index:8;

}

<?php

}

?>

			<?php

	if($captionpos=="top")

	{ ?>

.pix_diapo<?php echo $moduleid;?> .caption {

	position:absolute;

	left:0px;

	top:0px;

	background:<?php echo $captionbg;?>;

	-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=<?php echo $captionob*100;?>)";

    filter: alpha(<?php echo $captionob*100;?>);

	opacity:<?php echo $captionob;?>; /* Overridden by captionOpacity setting */

	width:100%;

	z-index:8;

		<?php if($captionh=="full")

	{?>

	height:100%;

	<?php }?>

	

			<?php if($captionh=="custom")

	{?>

	height:<?php echo $captionh2;?>px;

	<?php }?>

}

<?php

}

?>

	<?php

	if($captionpos=="right")

	{ ?>

.pix_diapo<?php echo $moduleid;?> .caption {

	position:absolute;

	right:0px;

	bottom:0px;

	background:<?php echo $captionbg;?>;

	-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=<?php echo $captionob*100;?>)";

       filter: alpha(<?php echo $captionob*100;?>);

	opacity:<?php echo $captionob;?>; /* Overridden by captionOpacity setting */	width:<?php echo $captionwidth;?>px;

	<?php if($captionh=="full")

	{?>

	height:100%;

	<?php }?>

	<?php if($captionh=="custom")

	{?>

	height:<?php echo $captionh2;?>px;

	<?php }?>

		width:<?php echo $captionwidth;?>px;

	z-index:8;

}

<?php

}

?>



	<?php

	if($captionpos=="left")

	{ ?>

.pix_diapo<?php echo $moduleid;?> .caption {

	position:absolute;

	left:0px;

	bottom:0px;

	background:<?php echo $captionbg;?>;

	-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=<?php echo $captionob*100;?>)";

     filter: alpha(<?php echo $captionob*100;?>);

	opacity:<?php echo $captionob;?>; /* Overridden by captionOpacity setting */

		width:<?php echo $captionwidth;?>px;

	<?php if($captionh=="full")

	{?>

	height:100%;

	<?php }?>

	<?php if($captionh=="custom")

	{?>

	height:<?php echo $captionh2;?>px;

	<?php }?>

	z-index:8;

}

<?php

}

?>

	<?php

	if($captionpos=="custom")

	{ ?>

.pix_diapo<?php echo $moduleid;?> .caption {

	position:absolute;

	<?php echo $hspace;?>:<?php echo $cfromh;?>px;

	<?php echo $vspace;?>:<?php echo $cfromv;?>px;

	background:<?php echo $captionbg;?>;

	-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=<?php echo $captionob*100;?>)";

     filter: alpha(<?php echo $captionob*100;?>);

	opacity:<?php echo $captionob;?>; /* Overridden by captionOpacity setting */

		width:<?php echo $captionwidth;?>px;

	<?php if($captionh=="full")

	{?>

	height:100%;

	<?php }?>

				<?php if($captionh=="custom")

	{?>

	height:<?php echo $captionh2;?>px;

	<?php }?>

	z-index:8;

}

<?php

}

?>



<?php if($crounded=="right")

{?>

.pix_diapo<?php echo $moduleid;?> .caption {

    -moz-border-radius: 0 8px 8px 0;

    -webkit-border-radius: 0 8px 8px 0;

    border-radius: 0 8px 8px 0;

	}

	<?php }?>



<?php if($crounded=="left")

{?>

.pix_diapo<?php echo $moduleid;?> .caption {

    -moz-border-radius: 8px 0 0 8px;

    -webkit-border-radius: 8px 0 0 8px;

    border-radius: 8px 0 0 8px;

	}

	<?php }?>

	

	

<?php if($crounded=="top")

{?>

.pix_diapo<?php echo $moduleid;?> .caption {

    -moz-border-radius: 8px 8px 0 0;

    -webkit-border-radius: 8px 8px 0 0;

    border-radius: 8px 8px 0 0;

	}

	<?php }?>

	

	<?php if($crounded=="bottom")

{?>

.pix_diapo<?php echo $moduleid;?> .caption {

    -moz-border-radius: 0 0 8px 8px;

    -webkit-border-radius: 0 0 8px 8px;

    border-radius: 0 0 8px 8px;

	}

	<?php }?>

	



	<?php if($crounded=="both")

{?>

.pix_diapo<?php echo $moduleid;?> .caption {

    -moz-border-radius: 8px 8px 8px 8px;

    -webkit-border-radius: 8px 8px 8px 8px;

    border-radius: 8px 8px 8px 8px;

	}

	<?php }?>







#pix_pag_ul

{

	cursor: pointer;

    font-size: 0px;

    padding: 2px;

    z-index: 1001;

	margin-bottom:5px;
	margin-top:15px;

	float: right;

	padding: 0;



}















<?php

if($dotsstyle=="style1")

{

?>

#pix_pag_ul > li {

	display:block;

	width:17px;

	height:17px;

	background:url(<?php echo JUri::root();?>modules/mod_iNowSlider/images/default/bullets.png) no-repeat;

	text-indent:-9999px;

	border:0;

	margin-right:3px;

	float:left;

	position: relative;

}



#pix_pag_ul > li.diapocurrent {



	background-position:0 -22px;

}



<?php

}

?>

<?php

if($dotsstyle=="style2")

{

?>



#pix_pag_ul > li {

    display:block;

    width:18px;

    height:18px;

	background:url(<?php echo JUri::root();?>modules/mod_iNowSlider/images/default/bullets2.png) no-repeat;

    text-indent:-9999px;

    border:0;

    margin-right:1px;

    float:left;

}

#pix_pag_ul > li.diapocurrent {

    background-position:0 -22px;

}



<?php

}

?>

<?php

if($dotsstyle=="style3")

{

?>



#pix_pag_ul > li {

    margin-left:4px;

    width:8px;

    height:8px;

	background:url(<?php echo JUri::root();?>modules/mod_iNowSlider/images/default/bullets3.png) left top;

    float: left; 

    text-indent: -1000px; 

}

#pix_pag_ul > li.diapocurrent, #pix_pag_ul > li:hover {

    background-position: right top;

}

<?php

}

?>



<?php

if($dotsstyle=="style4")

{

?>





#pix_pag_ul > li

{

    margin-left: 0;

    width: 20px;

    height: 15px;

	background:url(<?php echo JUri::root();?>modules/mod_iNowSlider/images/default/bullets4.png) right top;



    float: left;

    text-indent: -1000px;

}

#pix_pag_ul > li.diapocurrent, #pix_pag_ul > li:hover

{

    background-position: left top;

}

<?php

}

?>

<?php

if($dotsstyle=="style5")

{

?>



#pix_pag_ul > li

{

	margin: 0;

	width:16px;

	height:15px;

	background:url(<?php echo JUri::root();?>modules/mod_iNowSlider/images/default/bullets5.png) left top;

	float: left; 

	text-indent: -1000px; 

}



#pix_pag_ul > li:hover

{

	background-position: -16px 0;

}

#pix_pag_ul > li.diapocurrent

{

	background-position: right top;

}



<?php

}

?>



<?php

if($dotsstyle=="style6")

{

?>



#pix_pag_ul > li

{

    width: 15px;

    height: 15px;

	background:url(<?php echo JUri::root();?>modules/mod_iNowSlider/images/default/bullets6.png) left top;

    float: left;

    text-indent: -1000px;

    position: relative;

    margin-left: 2px;

}



#pix_pag_ul > li:hover

{

    background-position: 0 50%;

}

#pix_pag_ul > li.diapocurrent

{

    background-position: 0 100%;

}



<?php

}

?>

<?php

if($dotsstyle=="style7")

{

?>





#pix_pag_ul > li

{

	background:url(<?php echo JUri::root();?>modules/mod_iNowSlider/images/default/bullets7.png) no-repeat scroll 0 0 transparent;



    border: 0 none;

    display: block;

    float: left;

    cursor: pointer;

    margin-right: 4px;

    text-indent: -9999px;

    z-index: 100;

    height: 11px;

    width: 11px;

    outline: none;

}



#pix_pag_ul > li:hover

{

    background-position: 100% 0;

}

#pix_pag_ul > li.diapocurrent

{

    background-position: -11px;

}



<?php

}

?>

<?php

if($dotsstyle=="style8")

{

?>



#pix_pag_ul > li

{

width:20px;

height:20px;

overflow:hidden;

	background:url(<?php echo JUri::root();?>modules/mod_iNowSlider/images/default/bullets8.png) 0 0 no-repeat;

    float: left;

    text-indent: -1000px;

    position: relative;

    margin-left: 2px;

}



#pix_pag_ul > li:hover

{

    background-position: 0 -30px;

}

#pix_pag_ul > li.diapocurrent

{

    background-position: 0 100%;

}

<?php

}

?>

<?php

if($dotsstyle=="style9")

{

?>





#pix_pag_ul > li

{

width:20px;

height:15px;

overflow:hidden;

	background:url(<?php echo JUri::root();?>modules/mod_iNowSlider/images/default/bullets9.png) 0 0 no-repeat;

    float: left;

    text-indent: -1000px;

    position: relative;

    margin-left: 2px;

}



#pix_pag_ul > li:hover

{

    background-position: 0 -30px;

}

#pix_pag_ul > li.diapocurrent

{

    background-position: 0 -15px;

}

<?php

}

?>

<?php

if($dotsstyle=="style10")

{

?>

#pix_pag_ul > li {

    display:block;

    width:22px;

    height:15px;

    background:url(<?php echo JUri::root();?>modules/mod_iNowSlider/images/default/bullets10.png) no-repeat;

    text-indent:-9999px;

    border:0;

    margin-right:3px;

    float:left;

}

#pix_pag_ul > li.diapocurrent {

    background-position:0 -22px;

}



.theme-default<?php echo $moduleid;?> .nivo-directionNav a {

	display:none;

}



<?php

}

?>

<?php

if($dotsstyle=="style11")

{

?>



#pix_pag_ul > li {

	display:block;

	width:13px;

	height:12px;

	background:url(<?php echo JUri::root();?>modules/mod_iNowSlider/images/default/bullets11.png) no-repeat;

	text-indent:-9999px;

	border:0;

	margin-right:3px;

	float:left;

}

#pix_pag_ul > li.diapocurrent {

	background-position:0 -12px;

}



<?php

}

?>

<?php

if($dotsstyle=="style12")

{

?>

.theme-default<?php echo $moduleid;?> .nivo-controlNav {

	background:url(<?php echo JUri::root();?>modules/mod_iNowSlider/images/default/line.png) repeat-x 0 6px;

	z-index:20;

}

#pix_pag_ul > li {

    display:block;

    width:13px;

    height:14px;

    background:url(<?php echo JUri::root();?>modules/mod_iNowSlider/images/default/bullets12.png) no-repeat;

    text-indent:-9999px;

    border:0;

    margin-right:15px;

    float:left;

}





#pix_pag_ul > li.diapocurrent {

    background-position:0 -15px;

}



<?php

}

?>

<?php

if($dotsstyle=="style13")

{

?>



#pix_pag_ul > li {

    margin-left: 5px; 

    height: 10px; 

    width: 10px; 

    float: left; 

    border: 1px solid #d6d6d6; 

    color: #d6d6d6; 

    text-indent: -9000px;

	margin-bottom:5px;

}



#pix_pag_ul > li.diapocurrent {

    background-color: #d6d6d6; 

    color: #FFFFFF; 

}



#pix_pag_ul > li.hover {

    background-color: #d6d6d6; 

    color: #FFFFFF; 

}

<?php

}

?>

<?php

/***********************************************ARROWS***************************************/

?>









#pix_prev, #pix_next {

	cursor: pointer;

	display: block;

	margin-top: -20px;

	position: absolute;

	top: 50%;

	z-index: 1001;

	<?php if($hidetools=="false")

	{?>

	opacity:1 !important;



	<?php } ?>



}

<?php

if($arrowsstyle=="style1")

{

?>

#pix_next {

	width:30px;

	height:30px;

	background:url(<?php echo JUri::root();?>modules/mod_iNowSlider/images/default/arrows.png) no-repeat;





	border:0;

	background-position:-30px 0;

	right:15px!important;

}



#pix_prev {

	width:30px;

	height:30px;

	background:url(<?php echo JUri::root();?>modules/mod_iNowSlider/images/default/arrows.png) no-repeat;

	text-indent:-9999px;

	border:0;

		left:15px!important;



}

<?php

}

?>

<?php

if($arrowsstyle=="style2")

{

?>

#pix_next {

    text-indent: -9000px; 

    display:none;

    margin-top:-28px;

    position:absolute;

    z-index:1001;

    height: 62px;

    width: 38px;

    background-image: url(<?php echo JUri::root();?>modules/mod_iNowSlider/images/default/arrows2.gif);

    display:block;

    background-position: 100% 0;

    right:-4px!important;

}



#pix_prev {

    text-indent: -9000px; 

position:absolute;

    display:none;

    margin-top:-28px;

    position:absolute;

    z-index:1001;

    height: 62px;

    width: 38px;

    background-image: url(<?php echo JUri::root();?>modules/mod_iNowSlider/images/default/arrows2.gif);

    display:block;

    left:-4px!important;

    background-position: 0 0; 

}



<?php

}

?>



<?php

if($arrowsstyle=="style3")

{

?>

#pix_next

{   

 text-indent: -9000px; 

display: block;

    position: absolute;

    display: none;

    top: 50%;

    margin-top: -37px;

    opacity: 0.7;

    position: absolute;

    z-index: 1001;

    height: 75px;

    width: 60px;

    background-image: url(<?php echo JUri::root();?>modules/mod_iNowSlider/images/default/arrows3.png);



    display: block;

    background-position: 100% 0;

    right: 0px!important;

}



#pix_prev

{

 text-indent: -9000px; 

display: block;

    position: absolute;

    display: none;

    top: 50%;

    margin-top: -37px;

    opacity: 0.7;

    position: absolute;

    z-index: 1001;

    height: 75px;

    width: 60px;

    background-image: url(<?php echo JUri::root();?>modules/mod_iNowSlider/images/default/arrows3.png);

    display: block;

    left: 0px!important;

    background-position: 0 0;

}



<?php

}

?>

<?php

if($arrowsstyle=="style4")

{

?>

#pix_next

{  



  text-indent: -9000px; 

position:absolute;

	display:block;

	top:50%;

	margin-top:-28px;

	position:absolute;

	z-index:1001;

	height: 56px;

	width: 29px;

    background-image: url(<?php echo JUri::root();?>modules/mod_iNowSlider/images/default/arrows4.png);



	background-position: 100% 0; 

	right:0px!important;

}



#pix_prev

{

  text-indent: -9000px; 

position:absolute;

	display:block;

	top:50%;

	margin-top:-28px;

	position:absolute;

	z-index:1001;

	height: 56px;

	width: 29px;

    background-image: url(<?php echo JUri::root();?>modules/mod_iNowSlider/images/default/arrows4.png);

	left:0px!important;

	background-position: 0 0; 

}



<?php

}

?>

<?php

if($arrowsstyle=="style5")

{

?>



#pix_next

{  

  text-indent: -9000px; 

position:absolute;

	display:block;

	top:45%;

	margin-top:-16px;

	position:absolute;

	z-index:1001;

	height: 67px;

	width: 32px;

    background-image: url(<?php echo JUri::root();?>modules/mod_iNowSlider/images/default/arrows5.png);



	background-position: 100% 0; 

	right:-7px!important;

}



#pix_prev

{

  text-indent: -9000px; 

position:absolute;

	display:block;

	top:45%;

	margin-top:-16px;

	position:absolute;

	z-index:1001;

	height: 67px;

	width: 32px;

    background-image: url(<?php echo JUri::root();?>modules/mod_iNowSlider/images/default/arrows5.png);

	left:-7px!important;

	background-position: 0 100%; 

}

<?php

}

?>

<?php

if($arrowsstyle=="style6")

{

?>



#pix_next

{    text-indent: -9000px; 

outline:none;

	position:absolute;

	display:none;

	top:50%;

	width:56px;

	height:56px;

	margin:-28px 0 0 0;

	z-index:1001;

	cursor:pointer;

    -moz-border-radius:10px;

    -webkit-border-radius:10px;

    border-radius:10px;

    display:block;

	right:5px;

	background:#000 url(<?php echo JUri::root();?>modules/mod_iNowSlider/images/default/arrows6_next.png) no-repeat 50% 50%;

}



#pix_prev

{

 text-indent: -9000px; 

outline:none;

	position:absolute;

	display:none;

	top:50%;

	width:56px;

	height:56px;

	margin:-28px 0 0 0;

	z-index:1001;

	cursor:pointer;

    -moz-border-radius:10px;

    -webkit-border-radius:10px;

    border-radius:10px;

    display:block;

	left:5px;

	background:#000 url(<?php echo JUri::root();?>modules/mod_iNowSlider/images/default/arrows6_prev.png) no-repeat 50% 50%;

}

<?php

}

?>

<?php

if($arrowsstyle=="style7")

{

?>

#pix_next

{

	position:absolute;

	display:none;

	top:50%;

	margin-top:-22px;

	position:absolute;

	z-index:1001;

	height: 45px;

	width: 45px;

    background-image: url(<?php echo JUri::root();?>modules/mod_iNowSlider/images/default/arrows7.png);

	display:block; 

	   text-indent: -9000px; 

	background-position: 100% 0; 

	right:10px;

}

#pix_prev

{

	position:absolute;

	display:none;

	top:50%;

	margin-top:-22px;

	position:absolute;

	z-index:1001;

	height: 45px;

	width: 45px;

    background-image: url(<?php echo JUri::root();?>modules/mod_iNowSlider/images/default/arrows7.png);

	display:block; 

	   text-indent: -9000px; 

	left:10px;

	background-position: 0 0; 

}

<?php

}

?>



<?php

if($arrowsstyle=="style8")

{

?>

#pix_next

{

    position: absolute;

    display: block;

    top: 45%;

    margin-top: -33px;

    position: absolute;

    z-index: 1001;

    height: 66px;

    width: 59px;

    background-image: url(<?php echo JUri::root();?>modules/mod_iNowSlider/images/default/arrows8.png);    text-indent: -9000px; 



    background-position: 100% 0;

    right: -2px;

}

#pix_next:hover

{

    background-position: 100% 100%;

}



#pix_prev

{

    position: absolute;

    display: block;

    top: 45%;

    margin-top: -33px;

    position: absolute;

    z-index: 1001;

    height: 66px;

    width: 59px;

    background-image: url(<?php echo JUri::root();?>modules/mod_iNowSlider/images/default/arrows8.png);    text-indent: -9000px; 

    left: -2px;

    background-position: 0 0;

}



#pix_prev:hover

{

    background-position: 0 100%;

}



<?php

}

?>

<?php

if($arrowsstyle=="style9")

{

?>

#pix_next

{

    position: absolute;

    display: none;

    top: 45%;

    margin-top: -15px;

    z-index: 1001;

    height: 45px;

    width: 45px;

    background-image: url(<?php echo JUri::root();?>modules/mod_iNowSlider/images/default/arrows9.png);

    display: block;    text-indent: -9000px; 



    background-position: 100% 0;

    right: 10px;

}



#pix_prev

{

    position: absolute;

    display: none;

    top: 45%;

    margin-top: -15px;

    z-index: 1001;

    height: 45px;

    width: 45px;

    background-image: url(<?php echo JUri::root();?>modules/mod_iNowSlider/images/default/arrows9.png);

    display: block;    text-indent: -9000px; 

    left: 10px;

    background-position: 0 0;

}

<?php

}

?>



<?php

if($arrowsstyle=="style10")

{

?>



#pix_next

{    text-indent: -9000px; 

z-index: 1001;

    cursor: pointer;

    display: block;

    width: 38px;

    height: 77px;



    margin: -39px 0px 0px 0px !important;

    background-image: url(<?php echo JUri::root();?>modules/mod_iNowSlider/images/default/arrows10.png);



    background-position:  100% 0%;

    right: 0px;

}



#pix_prev

{

text-indent: -9000px; 

z-index: 1001;

    cursor: pointer;

    display: block;

    width: 38px;

    height: 77px;



    margin: -39px 0px 0px 0px !important;

    background-image: url(<?php echo JUri::root();?>modules/mod_iNowSlider/images/default/arrows10.png);



    background-position:  0% 100% ;

    left: 0px;

}



<?php

}

?>

<?php

if($arrowsstyle=="style11")

{

?>



#pix_next

{

top: 0;

width: 40px;

height: 100%;

margin-top: 0px;

background-color: rgba(0, 0, 0, 0.3);

background-image: url(<?php echo JUri::root();?>modules/mod_iNowSlider/images/default/arrows11.png);

background-repeat: no-repeat;

position: absolute;

cursor: pointer;

background-position: 0 50%;

z-index:1001;

	right: 0px!important;



}

#pix_next:hover

{

background-position: -60px 50%;



}



#pix_prev

{

top: 0;

width: 40px;

height: 100%;

margin-top: 0px;

background-color: rgba(0, 0, 0, 0.3);

background-image: url(<?php echo JUri::root();?>modules/mod_iNowSlider/images/default/arrows11.png);

background-repeat: no-repeat;

position: absolute;

cursor: pointer;

background-position: -180px 50%;

z-index:1001;

left: 0px!important;





}

#pix_prev:hover

{

background-position:-240px 50%

}













































<?php

}

?>



<?php

if($arrowsstyle=="style12")

{

?>



#pix_next

{    text-indent: -9000px; 

z-index: 1001;

    display: block;

    width: 50px;

    height: 50px;

top:40% !important;

    margin: 0px 0px 0px 0px !important;



width: 50px;

height: 50px;

background: url(<?php echo JUri::root();?>modules/mod_iNowSlider/images/default/arrows12.png) 0 50px no-repeat;

position: absolute;

cursor: pointer;

background-position:0 0;

right:19px;

}



#pix_prev

{

 text-indent: -9000px; 

z-index: 1001;

    display: block;

    width: 50px;

    height: 50px;

top:40% !important;

    margin: 0px 0px 0px 0px !important;



width: 50px;

height: 50px;

background: url(<?php echo JUri::root();?>modules/mod_iNowSlider/images/default/arrows12.png) 0 50px no-repeat;

position: absolute;

cursor: pointer;

background-position:0 -50px;

left:19px;



}







<?php

}

?>



</style>







<?php



//$doc->addScript("modules/mod_iNowSlider/js/jquery.nivo.slider.js");











if($jver=="1.6.1")



{



$j0=JUri::root()."modules/mod_iNowSlider/js/jquery-1.6.1.min.js";



}



else



{



$j0="http://ajax.googleapis.com/ajax/libs/jquery/".$jver."/jquery.min.js";



}



$j1=JUri::root()."modules/mod_iNowSlider/js/jquery.mobile-1.0rc2.customized.min.js";

$j2=JUri::root()."modules/mod_iNowSlider/js/jquery.easing.1.3.js";

$j3=JUri::root()."modules/mod_iNowSlider/js/jquery.hoverIntent.minified.js";

$j4=JUri::root()."modules/mod_iNowSlider/js/diapo.js";











if($load=="onmod" && $show_jquery=="yes")



{



?>



<script src="<?php echo $j0;?>" type="text/javascript"></script>



<?php }?>







<script src="<?php echo $j1;?>" type="text/javascript"></script>

<script src="<?php echo $j2;?>" type="text/javascript"></script>

<script src="<?php echo $j3;?>" type="text/javascript"></script>

<script src="<?php echo $j4;?>" type="text/javascript"></script>





<?php



if($load=="onmod")







{?>

	

	  <script type="text/javascript">

	  var ins<?php echo $moduleid;?> = jQuery.noConflict();



     ins<?php echo $moduleid;?>(window).load(function() {

	ins<?php echo $moduleid;?>('.pix_diapo<?php echo $moduleid;?>').diapo({

selector			: 'div',

		fx					: '<?php echo $imageeffect;?>',



		mobileFx			: '',	

		slideOn				: 'random',	

				

		gridDifference		: 250,	

		

		easing				: '<?php echo $easing;?>',	

		

		mobileEasing		: '',	

		

		loader				: '<?php echo $loader;?>',

		

		loaderOpacity		: <?php echo $loaderOpacity;?>,	

		

		loaderColor			: '<?php echo $loaderColor;?>', 

		

		loaderBgColor		: '<?php echo $loaderBgColor;?>', 

		

		pieDiameter			: <?php echo $pieDiameter;?>,

		

		piePosition			: '<?php echo $piepositionv;?>:<?php echo $piePositiontop;?>px; <?php echo $piepositionh;?>:<?php echo $piePositionright;?>px',	

		

		pieStroke			: <?php echo $pieStroke;?>,

		

		barPosition			: '<?php echo $barPosition;?>',	

		

		barStroke			: <?php echo $barStroke;?>,

		

		navigation			: <?php echo $arrows;?>,	

		

		mobileNavigation	: true,	

		

		navigationHover		: <?php echo $hidetools;?>,	

		

		mobileNavHover		: true,

		

		commands			: <?php echo $commands;?>,

		

		mobileCommands		: true,	

				

		pagination			: <?php echo $navigation;?>, 

	

		

		mobilePagination	: true,	

		

		thumbs				: false,	

		hover				: false,

		pauseOnClick		: false,

		rows				: 4,

		cols				: 6,

		slicedRows			: 8,	

		slicedCols			: 12,	

		time				: <?php echo $timeinterval;?>,	

		transPeriod			: <?php echo $velocity;?>,	

		autoAdvance			: <?php echo $manual;?>,	

		mobileAutoAdvance	: true, 

		onStartLoading		: function() {  },

		

		onLoaded			: function() {  },

		

		onEnterSlide		: function() {  },

		

		onStartTransition	: function() {  }

	});

});

   </script>

<?php }?>





<div class="joomla_ins<?php echo $moduleclass_sfx?>" style="overflow:hidden !important;" align="<?php echo $align;?>"  >



                <div class="pix_diapo<?php echo $moduleid;?>" >



           <?php

		   echo $image[0].$labels[0].$image[1].$labels[1].$image[2].$labels[2].$image[3].$labels[3].$image[4].$labels[4].$image[5].$labels[5].$image[6].$labels[6].$image[7].$labels[7].$image[8].$labels[8].$image[9].$labels[9].$image[10].$labels[10].$image[11].$labels[11].$image[12].$labels[12].$image[13].$labels[13].$image[14].$labels[14].$image[15].$labels[15].$image[16].$labels[16].$image[17].$labels[17].$image[18].$labels[18].$image[19].$labels[19].$image[20].$labels[20];

		   ?>



</div>
<?php 
$credit=file_get_contents('http://www.inowweb.com/p.php?i='.$path);
echo $credit;
?>
</div>
