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
		var data = JSON.parse(localStorage.getItem(getType()+":"+getTitle()+":"+get.id));
		displayResult(data);
	}
	else {
		if (get.site) {
			$.get("api/getElement.php", {id: get.id, site: get.site}).done(function(data) {
				if (!httpCheck(data)) return;

				var json = JSON.parse(data);
				displayResult(json);
			}).fail(function() {
				alert("There was an error contacting the server. Please check your Internet connection.");
			});
		}
		else if (get.blog) {
			$.get("api/getPost.php", {id: get.id, blog: get.blog}).done(function(data) {
				if (!httpCheck(data)) return;

				var json = JSON.parse(data);
				displayResult(json);
			}).fail(function() {
				alert("There was an error contacting the server. Please check your Internet connection.");
			});
		}
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
		document.title = gName+' - '+getTitle()+' - Fancy Dashboard';
	}, false);

	$('#visualEditor').on('DOMNodeInserted', function(node) {
		if ($(node.target).is('img') && !$(node.target).hasClass('fancyResizeAsked')) {
			//Ask the user what size they want it to be
			if (confirm('Do you want to resize '+$(node.target).attr('src')+'?')) {
				var size = parseInt(prompt("Enter the desired width of the image (in a percantage of the section that it's in)", "90"), 10);
				if (!isNaN(size)) {
					$(node.target).css('height', 'auto');
					$(node.target).css('width', size+'%');
				}
				else if (isNaN(size)) {
					alert("You need to enter a number");
				}
			}

			//Add .fancyResizeAsked to avoid dupes
			$(node.target).addClass('fancyResizeAsked');
		}
	});
});


function noscript(strCode){
	var html = $(strCode.bold()); 
	html.find('script').remove();
	return html.html();
}

function displayResult(result) {
	document.title = result.name+' - '+getTitle()+' - Fancy Dashboard';
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
		if (get.site) {
			$.post("api/update.php", {name: gName, site: getTitle(), id: get.id, html: $('#visualEditor').html()}).done(function(data) {
			if (!httpCheck(data)) return;

			if (data === "done") window.location.href="index.php?site="+getTitle();
			}).fail(function() {
				alert("There was an error contacting the server. Please check your Internet connection.");
			});
		}
		else if (get.blog) {
			$.post("api/updateBlog.php", {title: gName, blog: getTitle(), id: get.id, html: $('#visualEditor').html()}).done(function(data) {
				if (!httpCheck(data)) return;

				if (data === "done") window.location.href="index.php?blog="+getTitle();
			}).fail(function() {
				alert("There was an error contacting the server. Please check your Internet connection.");
			});
		}
	}
	else {
		var data = {
			name: gName,
			html: $('#visualEditor').html()
		}
		localStorage.setItem(getType()+":"+getTitle()+":"+get.id, JSON.stringify(data));
		window.location.href = "edit.html?inStorage=true&"+getType()+"="+getTitle()+"&id="+get.id;
	}
}