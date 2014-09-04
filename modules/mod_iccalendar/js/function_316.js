/**
 * iCagenda iCtip 2.1.10
 * © 2013 JoomliC
 *
 * update	2013-08-08
 *
 */

jQuery(function($){

	$('div#mod_iccalendar').on('click', '.icagendabtn', function(e){
		e.preventDefault();

		url=$(this).attr('href');

		$('div#mod_iccalendar').html('<div class="icloading_box"><div class="icloading_img"></div><div>'+Joomla.JText._('MOD_ICCALENDAR_LOADING', 'loading...')+'<div></div>').load(url+' #mod_iccalendar');

	});

});
