/* Language initialisation for the jQuery UI date picker plugin. */
/* Written by Keith Wood (kbwood{at}iinet.com.au) and Stéphane Nahmani (sholby@sholby.net). */
/* Modified by Cyril Rezé (Lyr!C) for iCagenda, joomla! extension - http://www.joomlic.com */
jQuery(function($){
	$.datepicker.regional[''] = {
		closeText: 'Done',
		prevText: '&#x3c;Prev',
		nextText: 'Next&#x3e;',
		currentText: 'Today',
		ampm: false,
		amNames: ['AM', 'A'],
		pmNames: ['PM', 'P'],
		timeFormat: 'hh:mm tt',
		timeSuffix: '',
		monthNames: [Joomla.JText._('JANUARY', 'January'),
		Joomla.JText._('FEBRUARY', 'February'),
		Joomla.JText._('MARCH', 'March'),
		Joomla.JText._('APRIL', 'April'),
		Joomla.JText._('MAY', 'May'),
		Joomla.JText._('JUNE', 'June'),
		Joomla.JText._('JULY', 'July'),
		Joomla.JText._('AUGUST', 'August'),
		Joomla.JText._('SEPTEMBER', 'September'),
		Joomla.JText._('OCTOBER', 'October'),
		Joomla.JText._('NOVEMBER', 'November'),
		Joomla.JText._('DECEMBER', 'December')],
		monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
		'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
		dayNames: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
		dayNamesShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
		dayNamesMin: [Joomla.JText._('SU', 'Su'),
		Joomla.JText._('MO', 'Mo'),
		Joomla.JText._('TU', 'Tu'),
		Joomla.JText._('WE', 'We'),
		Joomla.JText._('TH', 'Th'),
		Joomla.JText._('FR', 'Fr'),
		Joomla.JText._('SA', 'Sa')],
		weekHeader: 'Wk',
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''};
	$.datepicker.setDefaults($.datepicker.regional['']);
});



jQuery(function($){
	
	/*			
	$('#add').live('click', function(e){
		e.preventDefault();

		newdate=$('.data').attr('value')+', ';
		dates=$('.date').attr('value');
		$('.date').attr('value', dates+newdate);
		$('#dTable').append('<tr class="ddd"><td class="di">'+$('.data').attr('value')+'</td><td><a class="del" href="#">Delete</a></td></tr>');
		$('.data').attr('value', '');
	});

	

	$('.del').live('click', function(e){
		e.preventDefault();

		tr=$(this).parent('td').prev().html();
		$(this).parent('td').parent('tr').prev().html();
	});*/




	$( ".data" ).live('focus', function(){
		$(this).datetimepicker({ 
			dateFormat: 'yy-mm-dd',
			timeFormat: 'hh:mm',
			});
	});

	$('#add').live('click', function(e){
		e.preventDefault();
		$delete=Joomla.JText._('COM_ICAGENDA_DELETE_DATE', 'Delete');		
		$('#dTable').append('<tr><td><input class="data" type="text" name="d"/></td><td><a class="del" href="#">'+$delete+'</a></td></tr>');
	});

	$( ".ui-state-default" ).live('click', function(){
		$array=$('#dTable input').serialize();
		$('input.date').attr('value', $array);
	});

	$('.del').live('click', function(e){
		e.preventDefault();
		$(this).parent().parent('tr').remove();
		$array=$('#dTable input').serialize();
		$('input.date').attr('value', $array);
	});

});