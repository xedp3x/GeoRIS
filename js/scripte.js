function loadScript(scriptname) {  
	var snode = document.createElement('script');  
	snode.setAttribute('type','text/javascript');  
	snode.setAttribute('src',scriptname);  
	document.getElementsByTagName('head')[0].appendChild(snode);  
}  


var xmlhttp=false;
if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
	try {
		xmlhttp = new XMLHttpRequest();
	} catch (e) {
		xmlhttp=false;
	}
}
if (!xmlhttp && window.createRequest) {
	try {
		xmlhttp = window.createRequest();
	} catch (e) {
		xmlhttp=false;
	}
}


function mach(){
	xmlhttp.open("GET", command+list[x] ,true);
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4) {
			x +=1;
			if (x < list.length) {
				mach();
				document.getElementById("status3").style.width = ((x/list.length*100)) + "%";
				document.getElementById("counter3").innerHTML = Math.round(((x/list.length*100)))+" %";
			}else{
				document.getElementById('form').submit();
			}
		}
	}
	xmlhttp.send(null);	
}

var list;
var command;
var x;
function gruppenbefehl(IN,IN2){
	list 	= IN;
	command = IN2;
	x 		= 0;
	mach();
	return false;	
}