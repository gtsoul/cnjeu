/**
 * iCagenda iCtip 2.1.10
 * © 2013 JoomliC
 *
 * update	2013-05-07
 *
 */

(function($){
	$(document).on('click', '.icagendabtn', function(e){
		e.preventDefault();
		url=$(this).attr('href');
		$('#mod_iccalendar').load(url+' #mod_iccalendar');
	});
}) ( jQuery );