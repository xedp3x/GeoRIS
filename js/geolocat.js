var gl = null;
var glreq=false;

if (!glreq && typeof XMLHttpRequest!='undefined') {
	try {
		glreq = new XMLHttpRequest();
	} catch (e) {
		glreq=false;
	}
}
if (!glreq && window.createRequest) {
	try {
		glreq = window.createRequest();
	} catch (e) {
		glreq=false;
	}
}


function displayPosition(position) {
	glreq.open("GET", "/api.php?modul=geolocat&lat="+position.coords.latitude+"&lon="+position.coords.longitude+"&time="+position.timestamp ,true);
	glreq.onreadystatechange=function() {
		if (glreq.readyState==4) {
			if (glreq.responseText != "OK"){
				alert(glreq.responseText);
			}
		}
	}
	glreq.send(null);	
}

function displayError(positionError) {
	//alert("error")
}

try {
	if(typeof(navigator.geolocation) == 'undefined'){
		gl = google.gears.factory.create('beta.geolocation');
	} else {
		gl = navigator.geolocation;
	}
}catch(e){}

if (gl && glreq) {
	gl.getCurrentPosition(displayPosition, displayError);
} else {  
	//alert("I'm sorry, but geolocation services are not supported by your browser.");  
}