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
	try {
		document.getElementById('click_lat').value = position.coords.latitude;
		document.getElementById('click_lon').value = position.coords.longitude;
	} catch (e) {}

	try {
		document.getElementById('geolocat_click').value = 'Fertig';
	} catch (e) {}

	glreq.open("GET", "/api.php?modul=geolocat&lat="+position.coords.latitude+"&lon="+position.coords.longitude+"&time="+position.timestamp ,true);
	glreq.onreadystatechange=function() {
	}
	glreq.send(null);	
}

function displayError(positionError) {
	try {
		document.getElementById('geolocat_click').value = 'Fehler';
		alert("Ihr Browser unterstützt diese Funktion leider nicht.");
	} catch (e) {}  
}

try {
	if(typeof(navigator.geolocation) == 'undefined'){
		gl = google.gears.factory.create('beta.geolocation');
	} else {
		gl = navigator.geolocation;
	}
}catch(e){}

if (gl && glreq) {
	try {
		document.getElementById('geolocat_click').value = 'Abfrage Läuft';
	} catch (e) {}
	gl.getCurrentPosition(displayPosition, displayError);
} else {  
	try {
		document.getElementById('geolocat_click').value = 'Fehler';
		alert("Ihr Browser unterstützt diese Funktion leider nicht.");
	} catch (e) {}  
}