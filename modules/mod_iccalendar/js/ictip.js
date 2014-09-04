/**
 * iCagenda iCtip 2.1.1
 * © 2013 JoomliC
 *
 * update	2013-03-14
 *
 */

var icmouse;
var icclasstip;
var icclass;
var posit;

jQuery(function ($){	$(document).on(icmouse, icclasstip, function(e){		e.preventDefault();		$('#ictip').remove();		$parent=$(this).parent();		$tip=$($parent).children('.spanEv').html();				//Left
		if (posit=='left') {
			$width='390px';
			$pos=$(icclass).offset().left -450+'px';			$top=$(icclass).offset().top -45+'px';
		}		//Right
		if (posit=='right') {
			$width='390px';
			$pos=$(icclass).offset().left+$(icclass).width()+10+'px';			$top=$(icclass).offset().top -45+'px';		}		//Center
		if (posit=='center') {
			$width='25%';
			$pos='36%';			$top=$(icclass).offset().top-$(icclass).height()+0+'px';		}		//Top Center
		if (posit=='topcenter') {
			$width='25%';
			$pos='36%';			$top=$(icclass).offset().top-$(icclass).height()+0+'px';		}		//Bottom Center
		if (posit=='bottomcenter') {
			$width='25%';
			$pos='36%';			$top=$(icclass).offset().top+$(icclass).height()+0+'px';		}				$('body').append('<div style="position:absolute; width:'+$width+'; left:'+$pos+'; top:'+$top+';" id="ictip"> '+$(this).parent().children('.date').html()+'<a class="close">X</a><span class="clr"></span>'+$tip+'</div>');		$(document).on('click', '#ictip .close', function(e){			e.preventDefault();			$('#ictip').remove();		});	});});
