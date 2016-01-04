String.prototype.addSlashes = function() {
	return this.replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
}


$(document).ready(function() {
	/* API Requests START */
	$.get("api/getElement.php", {id: get.id, site: get.site}).done(function(data) {
		if (data == "Not enough data has been sent") {
			alert("Not enough data has been sent");
			return;
		}

		var json = JSON.parse(data);
		displayResult(json);
		updateHint();
	}).fail(function() {
		alert("There was an error contacting the server. Please check your Internet connection.");
	});

	if (localStorage.getItem('defaultEditor') == 'power') $('#defaulter').hide();

	function displayResult(result) {
		$('#name').text(result.name);
		document.title = result.name+' - '+get.site+' - Fancy Dashboard';
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

	$('#md').bind('input propertychange', function() {
		$('#visualEditor').html(markdown.toHTML(this.value));
		$('#htmleditor').val(markdown.toHTML(this.value));
	});

	$('#htmleditor').bind('input propertychange', function() {
		$('#visualEditor').html(noscript(this.value));
		$('#md').val(toMarkdown(this.value, { gfm: true }));
	});

	$('#visualEditor').bind('input propertychange', function() {
		$('#htmleditor').val($(this).html());
		$('#md').val(toMarkdown($(this).html(), { gfm: true }));
	});

	function updateHint() {
		var raw = $('#name').text();
		var formatted = $('<textarea />').html(raw).val();
		$('#nameInput').val(formatted);
		$('#nameCode').html(formatted.addSlashes());
		document.title = formatted+' - '+get.site+' - Fancy Dashboard';
	}
	
	document.getElementById("name").addEventListener("input", function() {
		updateHint();
	}, false);

	//Handle DOCX stuff
	document.getElementById("docxUpload").addEventListener("change", handleFileSelect, false);
	function handleFileSelect(event) {
		readFileInputEventAsArrayBuffer(event, function(arrayBuffer) {
			mammoth.convertToHtml({arrayBuffer: arrayBuffer})
				.then(displayResult)
				.done();
		});
	}
});

function del(name) {
	if (confirm('Are you sure you want to delete this?')) {
		$.get("api/delete.php", {type: "element", site: get.site, name: name}).done(function(data) {
			if (data != "done") {
				alert(data);
				return;
			}

			window.location.href="."
		}).fail(function() {
			alert("There was an error contacting the server. Please check your Internet connection.");
		});
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

function changeEditor() {
	var raw = $('#name').text();
	var formatted = $('<textarea />').html(raw).val();
	$('<form action="simpleEditor.php?site='+get.site+'&id='+get.id+'" method="POST">' +
	  '<input type="hidden" name="html" value="'+encodeURIComponent($('#visualEditor').html())+'">' +
	  '<input type="hidden" name="name" value="'+encodeURIComponent(formatted)+'">' +
	  '</form>').appendTo("body").submit();
}

function readFileInputEventAsArrayBuffer(event, callback) {
	var file = event.target.files[0];

	var reader = new FileReader();
	
	reader.onload = function(loadEvent) {
		var arrayBuffer = loadEvent.target.result;
		callback(arrayBuffer);
	};
	
	reader.readAsArrayBuffer(file);
}