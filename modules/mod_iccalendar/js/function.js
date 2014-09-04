/**
 * iCagenda iCtip 3.1.7
 * © 2013 JoomliC
 *
 * update	2013-08-27
 *
 */



jQuery(function($){

	$(document).on('click', icagendabtn, function(e){
		e.preventDefault();

		url=$(this).attr('href');

		$(mod_iccalendar).html('<div class="icloading_box"><div class="icloading_img"></div><div>'+Joomla.JText._('MOD_ICCALENDAR_LOADING', 'loading...')+'<div></div>').load(url+' '+mod_iccalendar);

	});

});

