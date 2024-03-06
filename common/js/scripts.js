function setVisible(what) {
	elem = document.getElementById(what);
	elem.style.display = "block";
}

function setInvisible(what) {
	elem = document.getElementById(what);
	elem.style.display = "none";
}

function showBib(pubId) {
	
	elem = document.getElementById("bibBox");
	elem.style.display = "block";
	
	var boxes = document.getElementsByTagName("div");
	for (b = 0; b < boxes.length; b++) {
		bclass = boxes[b].getAttribute("class");
		if (bclass == "bibItem"){
			boxes[b].style.display = "none";
		}
	}
	bname = "bib"+pubId;
	elem = document.getElementById(bname);
	elem.style.display = "block";
}