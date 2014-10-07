
jQuery(document).ready(function($){
	$("a[rel^='prettyPhoto']").prettyPhoto();
});

function initialize(){
	var mapProp = {
		center:new google.maps.LatLng(43.067037,-76.119383),
		zoom:16,
		mapTypeId:google.maps.MapTypeId.ROADMAP,
		panControl:false,
		zoomControl:false,
		mapTypeControl:false,
		scaleControl:false,
		streetViewControl:false,
		overviewMapControl:false,
		rotateControl:false
	};
	var map=new google.maps.Map(document.getElementById("googleMap")
  		,mapProp);
	var marker=new google.maps.Marker({
  		position:new google.maps.LatLng(43.067037,-76.119383),
	});

	marker.setMap(map);
}

google.maps.event.addDomListener(window, 'load', initialize);


	