var gName = '';
String.prototype.addSlashes = function() {
	return this.replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
}

$(document).ready(function() {
	if (get.id === '0' && !get.inStorage) {
		var data = {
			name: "Enter name here",
			html: ""
		}
		if (get.blog) data.name = "Enter title here";
		displayResult(data);
		updateHint();
	}
	else if (get.inStorage) {
		var data = JSON.parse(localStorage.getItem(getType()+":"+getTitle()+":"+get.id));
		console.log(data);
		displayResult(data);
		updateHint();
	}
	else {
		if (get.site) {
			$.get("api/getElement.php", {id: get.id, site: get.site}).done(function(data) {
				if (!httpCheck(data)) return;

				var json = JSON.parse(data);
				displayResult(json);
				updateHint();
			}).fail(function() {
				alert("There was an error contacting the server. Please check your Internet connection.");
			});
		}
		else if (get.blog) {
			$.get("api/getPost.php", {id: get.id, blog: get.blog}).done(function(data) {
				if (!httpCheck(data)) return;

				var json = JSON.parse(data);
				displayResult(json);
				updateHint();
			}).fail(function() {
				alert("There was an error contacting the server. Please check your Internet connection.");
			});
		}
	}

	if (get.site) {
		$('#newButton').html('<i class="fa fa-fw fa-plus"></i> New Element');

		$.get("api/getElements.php", {site: get.site}).done(function(data) {
			if (!httpCheck(data)) return;

			var json = JSON.parse(data);
			for (var i = 0; i <= json.length - 1; i++) {
				var code = '<li>';
				if (json[i].name.toLowerCase() === $('#name').text().toLowerCase()) code = '<li class="active">';
				code += "<a href='edit.html?site="+get.site+"&id="+json[i].id+"'>"+json[i].name+"</a></li>";
				$('#elementSidebar').append(code);
			};
		}).fail(function() {
			alert("There was an error contacting the server. Please check your Internet connection.");
		});
	}
	else if (get.blog) {
		$('#newButton').html('<i class="fa fa-fw fa-pencil"></i> Write a new post');
		$('#instructions').hide();

		$.get("api/getPosts.php", {blog: get.blog}).done(function(data) {
			if (!httpCheck(data)) return;

			var json = JSON.parse(data);
			for (var i = json.length - 1; i >= 0; i--) {
				var code = '<li>';
				if (json[i].title.toLowerCase() === $('#name').text().toLowerCase()) code = '<li class="active">';
				code += "<a href='edit.html?blog="+get.blog+"&id="+json[i].id+"'>"+json[i].title+"</a></li>";
				$('#elementSidebar').append(code);
			};
		}).fail(function() {
			alert("There was an error contacting the server. Please check your Internet connection.");
		});
	}

	if (localStorage.getItem('defaultEditor') == 'power') $('#defaulter').hide();

	function displayResult(result) {
		$('#name').text(result.name);
		document.title = result.name+' - '+getTitle()+' - Fancy Dashboard';
		$('#htmleditor').val(result.html);
		$('#visualEditor').html(noscript(result.html));
		$('#md').val(toMarkdown(result.html, { gfm: true }));
	}

	emmet.require('textarea').setup({
		pretty_break: true,
		use_tab: true
	});

	var editor = new MediumEditor('#visualEditor', {
		placeholder: {
			text : ''
		},
		buttons: ["bold", "italic", "underline", "header1", "header2", "anchor", "image", "quote", "removeFormat"],
		firstHeader: 'h1',
		secondHeader: 'h2'
	});

	var simpleEditing = false;

	$('#md').bind('input propertychange', function() {
		simpleEditing = false;
		$('#visualEditor').html(markdown.toHTML(this.value));
		$('#htmleditor').val(markdown.toHTML(this.value));
	});

	$('#htmleditor').bind('input propertychange', function() {
		simpleEditing = false;
		$('#visualEditor').html(noscript(this.value));
		$('#md').val(toMarkdown(this.value, { gfm: true }));
	});

	$('#visualEditor').bind('input propertychange', function() {
		simpleEditing = true;
		$('#htmleditor').val($(this).html());
		$('#md').val(toMarkdown($(this).html(), { gfm: true }));
	});

	$('#visualEditor').on('DOMNodeInserted', function(node) {
		if ($(node.target).is('img') && !$(node.target).hasClass('fancyResizeAsked') && simpleEditing) {
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

	function updateHint() {
		var raw = $('#name').text();
		gName = $('<textarea />').html(raw).val();
		$('#nameCode').html("'"+gName.addSlashes()+"'");
		document.title = gName+' - '+getTitle()+' - Fancy Dashboard';
	}
	
	document.getElementById("name").addEventListener("input", function() {
		updateHint();
	}, false);

	//Form prefilling
	$('input[name="id"]').val(get.id);
	$('input[name="site"]').val(getTitle());

	//Handle DOCX stuff
	document.getElementById("docxUpload").addEventListener("change", handleFileSelect, false);
	function handleFileSelect(event) {
		readFileInputEventAsArrayBuffer(event, function(arrayBuffer) {
			mammoth.convertToHtml({arrayBuffer: arrayBuffer})
				.then(displayDocx)
				.done();
		});
	}

	function displayDocx(result) {
		var docxHTML = result.value.replace(/\\"/g, '"');
		$('#htmleditor').val(docxHTML);
		$('#visualEditor').html(noscript(docxHTML));
		$('#md').val(toMarkdown(docxHTML, { gfm: true }));
	}
});

function readFileInputEventAsArrayBuffer(event, callback) {
	var file = event.target.files[0];

	var reader = new FileReader();
	
	reader.onload = function(loadEvent) {
		var arrayBuffer = loadEvent.target.result;
		callback(arrayBuffer);
	};
	
	reader.readAsArrayBuffer(file);
}

function del(name) {
	if (confirm('Are you sure you want to delete this?')) {
		if (get.site) {
			$.get("api/delete.php", {type: "element", site: getTitle(), name: name}).done(function(data) {
				if (data === "Authentication Error") {
					window.location.href = "login.html";
					return;
				}
				else if (data != "done") {
					alert(data);
					return;
				}

				window.location.href="index.php?site="+getTitle();
			}).fail(function() {
				alert("There was an error contacting the server. Please check your Internet connection.");
			});
		}
		else if (get.blog) {
			$.get("api/delete.php", {type: "post", blog: getTitle(), name: name}).done(function(data) {
				if (data === "Authentication Error") {
					window.location.href = "login.html";
					return;
				}
				else if (data != "done") {
					alert(data);
					return;
				}

				window.location.href="index.php?blog="+getTitle();
			}).fail(function() {
				alert("There was an error contacting the server. Please check your Internet connection.");
			});
		}
	} else {
		return;
	}
}

function noscript(strCode){
	var html = $(strCode.bold()); 
	html.find('script').remove();
	return html.html();
}

function fullscreenMe(element) {
	var element = document.getElementById(element);
	var turningFullscreen = false;
	$(element).css("min-height", "100%");

	//Handle changing CSS back when we go out of full screen
	$(document).bind("webkitfullscreenchange mozfullscreenchange fullscreenChange MSFullscreenChange", function() {
		if (turningFullscreen) {
			turningFullscreen = false;
			return;
		}

		if (!document.fullScreen && !document.mozFullScreen && !document.webkitIsFullScreen && isMSFullscreen && !turningFullscreen) {
			isMSFullscreen = false;
			$(element).css("min-height", "310px");
		}
	});
	
	var isMSFullscreen = false;
	if (element.requestFullscreen) {
		isMSFullscreen = true;
		element.requestFullscreen();
	}
	else if (element.msRequestFullscreen) {
		turningFullscreen = true;
		isMSFullscreen = true;
		element.msRequestFullscreen();
	}
	else if (element.mozRequestFullScreen) {
		isMSFullscreen = true;
		element.mozRequestFullScreen();
	}
	else if (element.webkitRequestFullscreen) {
		isMSFullscreen = true;
		element.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
	}
	else {
		alert("Your browser doesn't support fullscreen mode");
	}
}

function save() {
	if (get.site) {
		$.post("api/update.php", {name: gName, site: getTitle(), id: get.id, html: $('#htmleditor').val()}).done(function(data) {
			if (!httpCheck(data)) return;

			if (data === "done") {
				showSnackbar("Element updated successfully");
			}
		}).fail(function() {
			alert("There was an error contacting the server. Please check your Internet connection.");
		});
	}
	else if (get.blog) {
		$.post("api/updateBlog.php", {title: gName, blog: getTitle(), id: get.id, html: $('#htmleditor').val()}).done(function(data) {
			if (!httpCheck(data)) return;

			if (data === "done") {
				showSnackbar("Element updated successfully");
			}
		}).fail(function() {
			alert("There was an error contacting the server. Please check your Internet connection.");
		});
	}
}

function changeEditor() {
	var data = {
		name: gName,
		html: $('#visualEditor').html()
	}
	localStorage.setItem(getType()+":"+getTitle()+":"+get.id, JSON.stringify(data));
	window.location.href = "simpleEditor.html?inStorage=true&"+getType()+"="+getTitle()+"&id="+get.id;
}

