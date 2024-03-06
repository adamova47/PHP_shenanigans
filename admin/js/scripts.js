function setTask(task, id, pagepart) {
	if (task != null) {
		document.getElementById('task').value = task;
		if (id != null) 
			document.getElementById('id').value = id;
		if (pagepart != null) 
			document.getElementById('pagepart').value = pagepart;
		if (task.indexOf("delete") >= 0) {
			var answer = confirm("Do you really want to delete this?")
			if (answer){
				document.main.submit();
			}
		} else {
			if (task == "newEntry") {
				if (document.getElementById('name').value != "" && document.getElementById('title').value != "" &&
					document.getElementById('author').value != "" && document.getElementById('year').value != "")
					document.main.submit();
				else 
					alert("Unable to add entry.\nMissing some obligatory field(s).");
			} else {
				document.main.submit();	
			}
		}
	}
}

function editEntry(id) {
	document.getElementById('task').value = "invokeEdit"; 
	if (id != null) {
		document.getElementById('id').value = id; 
		var loc = document.location+"";
		if (loc.indexOf("&id") > 0) {
			loc = loc.substring(0, loc.indexOf("&id", 0));
		}
		document.main.submit();
		document.location = loc+"&id="+id;
	}
}

function editLatex(id) {
	document.getElementById('task').value = "editLatex"; 
	if (id != null) {
		document.getElementById('id').value = id; 
		var loc = document.location+"";
		if (loc.indexOf("&id") > 0) {
			loc = loc.substring(0, loc.indexOf("&id", 0));
		}
		document.main.submit();
	}
}

function importLatex(id) {
	document.getElementById('task').value = "importLatex"; 
	if (id != null) {
		document.getElementById('id').value = id; 
		document.main.submit();
	}
}

function editProject(id) {
	document.getElementById('task').value = "invokeEdit";
	document.getElementById('part').value = "projects"; 
	if (id != null) {
		document.getElementById('id').value = id;
		document.main.submit();	
	}
}

function makePublic(id,vis,tablename) {
	if (id != null) {
		document.getElementById('id').value = id;
		document.getElementById('changeVis').value = vis;
		document.getElementById('tablename').value = tablename;  
		document.getElementById('task').value = "makePublic";
		document.main.submit();
	}
}

function clearTask() {
	document.getElementById('task').value = "";
	document.getElementById('id').value = -1;
	document.main.submit();
}

function insertAtCaret(element, text) {
    if (document.selection) {
        element.focus();
        var sel = document.selection.createRange();
        sel.text = text;
        element.focus();
    } else if (element.selectionStart || element.selectionStart === 0) {
        var startPos = element.selectionStart;
        var endPos = element.selectionEnd;
        var scrollTop = element.scrollTop;
        element.value = element.value.substring(0, startPos) + text + element.value.substring(endPos, element.value.length);
        element.focus();
        element.selectionStart = startPos + text.length;
        element.selectionEnd = startPos + text.length;
        element.scrollTop = scrollTop;
    } else {
        element.value += text;
        element.focus();
    }
}


function addHTML(what, where) {
	entity = document.getElementById(where);
	if (entity.value != "") {
		insertAtCaret(entity,"\n"+what);
	}
	else 
		;
}

function addLinkPaper(where) {
	entity = document.getElementById(where);
	selectbox = document.getElementById('linkPaper');
	if (entity != null) {
		link = "?action=publications#pub"+selectbox.options[selectbox.selectedIndex].value;
		entity.value += '<a href="'+link+'">'+selectbox.options[selectbox.selectedIndex].text+'</a>' + "\n";
	}
	entity.scrollTop = entity.scrollHeight;
}

function addLinkProject(where) {
	entity = document.getElementById(where);
	selectbox = document.getElementById('linkProject');
	if (entity != null) {
		link = "?action=projects#proj"+selectbox.options[selectbox.selectedIndex].value;
		entity.value += '<a href="'+link+'">'+selectbox.options[selectbox.selectedIndex].text+'</a>' + "\n";
	}
	entity.scrollTop = entity.scrollHeight;
}

function addPubList(where) {
	
	//get values from page
	usersElem = document.getElementById('listUsers[]');
	usersArr = new Array();
	if (usersElem != null) {
		for (var i = 0; i < usersElem.options.length; i++) {
			if (usersElem.options[i].selected) 
				usersArr.push(usersElem.options[i].value);
		}
	}
	tagsElem = document.getElementById('listTags[]');
	tagsArr = new Array(); 
	if (usersElem != null) {
		for (var i = 0; i < tagsElem.options.length; i++) {
			if (tagsElem.options[i].selected) 
				tagsArr.push(tagsElem.options[i].value);
		}
	}
	
	//get last invisible box id
	object = document.getElementById(where);
	newId = object.value;
	if (newId.indexOf("invisbox") > 0) {
		newId = newId.substring(newId.lastIndexOf("invisbox")+8,newId.length);
		newId = newId.substring(0,newId.indexOf("\""));
	}
	newId = parseInt(newId);
	if (isNaN(newId)) {
		//first invisible div
		newId = 0;
	} else {
		//new next invisible div
		newId++; 
	}
	
	visCount = document.getElementById('visibleCount').value;
	
	try {
		xmlhttp = new XMLHttpRequest();
	} catch (e) {
	//alert("You need a browser which supports an XMLHttpRequest Object.")
	}
	if (xmlhttp) { 
		xmlhttp.open("GET", "utils/publiList.php?users="+JSON.stringify(usersArr)+"&tags="+JSON.stringify(tagsArr)+"&invisId="+newId+"&visibleCount="+visCount);
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState==4 && xmlhttp.status==200) {
				object = document.getElementById(where);
				object.value += xmlhttp.responseText;
			}
		} 
		xmlhttp.send(null);
	}
}

function exportBibLib() {
	document.forms["expbibform"].submit();
}

function getValue(what) {
	entity = document.getElementById(what);
	return entity.value;
}

function importLaTex(id) {
	if (id != null) {
		document.getElementById('task').value = "importLaTex";
		document.getElementById('id').value = id;
		document.main.submit();
	}
}

