window.onload = function() {
	galerii=document.getElementById('galeriiDiv');
	if (galerii != null) {	
		// lisada sÃƒÂ¼ndmus
		lingid=galerii.getElementsByTagName("img");
		for (i=0; i<lingid.length; i++){
			lingid[i].onclick=function(){
				showDetails(this);
				return false; // vajalik, et link ei viiks ara
			}
		}
	}
}

window.onresize=function() {
	suurpilt=document.getElementById("suurpilt");
	suurus(suurpilt);
}


function showDetails(el){
	hoidja=document.getElementById('hoidja');
	if (hoidja != null) {
		suurpilt=document.getElementById("suurpilt");
		suurpilt.src=el.parentNode.href;
		suurpilt.onload=function(){
				suurus(this);
		}
		document.getElementById("inf").innerHTML=el.alt;
		hoidja.style.display="initial";
	}

}
function hideDetails() {
	document.getElementById('hoidja').style.display="none";
}
// see on ette antud
function suurus(el){
	el.removeAttribute("height");
	el.removeAttribute("width");
	if (el.width>window.innerWidth || el.height>window.innerHeight){
		// ainult liiga suure pildi korral
		if (window.innerWidth >= window.innerHeight){
			el.height=window.innerHeight*0.9;
			//console.log('ekraan on lapik')
			// kas element lÃƒÂ¤heb ikka ÃƒÂ¼le piiri?
			if (el.width>window.innerWidth){
				el.removeAttribute("height");
				el.width=window.innerWidth*0.9;
			}
		} else {
			el.width=window.innerWidth*0.9;	
			//console.log("ekraan on piklik")
			// kas element lÃƒÂ¤heb ikka ÃƒÂ¼le piiri?
			if (el.height>window.innerHeight){
				el.removeAttribute("width");
				el.height=window.innerHeight*0.9;
			}
		}
	}
} //J2rgnev ei ole minu loodud!

function test(element) {
	if(element.style.color != "white") {
		element.style.color = "white";
	} else
	element.style.color = "#a38a65";
}

function test2() {
	var menuList = document.getElementById("menuList");
	var a = menuList.getElementsByTagName("a");
	var i;

	for (i = 0; i < a.length; i++) {
		if(a[i].style.backgroundColor != "#00aeff") {
			a[i].style.backgroundColor = "#00aeff";
		} else 
		a[i].style.backgroundColor = "#7cbe42";
	}
}