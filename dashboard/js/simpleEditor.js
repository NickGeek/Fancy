var gName = '';

$(document).ready(function() {
	if (get.id === '0' && !get.inStorage) {
		var data = {
			name: "Enter name here",
			html: ""
		}
		displayResult(data);
	}
	else if (get.inStorage) {
		var data = JSON.parse(localStorage.getItem(get.site+":"+get.id));
		displayResult(data);
	}
	else {
		$.get("api/getElement.php", {id: get.id, site: get.site}).done(function(data) {
			if (!httpCheck(data)) return;

			var json = JSON.parse(data);
			displayResult(json);
		}).fail(function() {
			alert("There was an error contacting the server. Please check your Internet connection.");
		});
	}

	var editor = new MediumEditor('#visualEditor', {
		placeholder: {
			text: ''
		},
		buttons: ["bold", "italic", "underline", "header1", "header2", "anchor", "image", "quote", "removeFormat"],
		firstHeader: 'h1',
		secondHeader: 'h2'
	});

	if (localStorage.getItem('defaultEditor') == 'simple') $('#defaulter').hide();

	document.getElementById("name").addEventListener("input", function() {
		var raw = $('#name').text();
		gName = $('<textarea />').html(raw).val();
		document.title = gName+' - '+get.site+' - Fancy Dashboard';
	}, false);
});


function noscript(strCode){
	var html = $(strCode.bold()); 
	html.find('script').remove();
	return html.html();
}

function displayResult(result) {
	document.title = result.name+' - '+get.site+' - Fancy Dashboard';
	$('#name').text(result.name);
	gName = result.name;
	$('#visualEditor').html(noscript(result.html));
	if (result.html.length == 0) {
		console.log(result.html.length);
		$('#visualEditor').attr("data-placeholder", "Click here to start editing");
	}
}

function save(publish) {
	if (publish) {
		$.post("api/update.php", {name: gName, site: get.site, id: get.id, html: $('#visualEditor').html()}).done(function(data) {
		if (!httpCheck(data)) return;

		if (data === "done") window.location.href="index.php?site="+get.site;
		}).fail(function() {
			alert("There was an error contacting the server. Please check your Internet connection.");
		});
	}
	else {
		var data = {
			name: gName,
			html: $('#visualEditor').html()
		}
		localStorage.setItem(get.site+":"+get.id, JSON.stringify(data));
		window.location.href = "edit.html?inStorage=true&site="+get.site+"&id="+get.id;
	}
}