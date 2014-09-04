$(function() {

  var fixICalendar = function() {
    var $cal = $('.iccalendar  .icnav');
    $cal.find('div').each(function(it) {
      if($(this).attr('href') != undefined && $(this).attr('href') !='') {
        var $a = $('<a/>');
        $a.attr({'href': $(this).attr('href'), 'class': 'cnj-ical-nav cnj-ical-nav-'+it, 'label': $(this).attr('class')});
        $cal.append($a);        
        $(this).remove();    
      } else {
        $cal.append($(this));
      }
    });
  };
  fixICalendar(); 
});