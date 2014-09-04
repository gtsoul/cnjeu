var lat;
var lng;
var id;

function initialize(lat, lng, id){
  var latlng = new google.maps.LatLng(lat, lng);
  var mapOptions = {
    zoom: 16,
    center: latlng,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };

  var map = new google.maps.Map(document.getElementById('map_canvas'+id), mapOptions);

  var geocoder = new google.maps.Geocoder();

  // marker
  var icagendaimage = new google.maps.MarkerImage('http://www.google.com/mapfiles/marker.png',
      new google.maps.Size(40, 35),
      new google.maps.Point(0,0),
      new google.maps.Point(20, 35));
  var shadow = new google.maps.MarkerImage('http://www.google.com/mapfiles/shadow50.png',
      new google.maps.Size(62, 35),
      new google.maps.Point(0,0),
      new google.maps.Point(20, 35));
  var shape = {
      coord: [1, 1, 1, 40, 40, 40, 40, 1],
      type: 'poly'
  };

  var marker = new google.maps.Marker({
    map: map,
    shadow: shadow,
    icon: icagendaimage,
    shape: shape,
    draggable: false,
	position: latlng
  });
}

//google.maps.event.addDomListener(window, 'load', initialize);
