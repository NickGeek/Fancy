String.prototype.addSlashes = function() {
	return this.replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
}

$(document).ready(function() {
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
	
	document.getElementById("name").addEventListener("input", function() {
		var raw = $('#name').text();
		var formatted = $('<textarea />').html(raw).val();
		$('#nameInput').val(formatted);
		$('#nameCode').html(formatted.addSlashes());
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

function readFileInputEventAsArrayBuffer(event, callback) {
	var file = event.target.files[0];

	var reader = new FileReader();
	
	reader.onload = function(loadEvent) {
		var arrayBuffer = loadEvent.target.result;
		callback(arrayBuffer);
	};
	
	reader.readAsArrayBuffer(file);
}

function displayResult(result) {
	$('#htmleditor').val(result.value);
	$('#visualEditor').html(noscript(result.value));
	$('#md').val(toMarkdown(result.value, { gfm: true }));
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
		element.requestFullscreen();
	}
	else if (element.msRequestFullscreen) {
		turningFullscreen = true;
		isMSFullscreen = true;
		element.msRequestFullscreen();
	}
	else if (element.mozRequestFullScreen) {
		element.mozRequestFullScreen();
	}
	else if (element.webkitRequestFullscreen) {
		element.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
	}
	else {
		alert("Your browser doesn't support fullscreen mode");
	}
}