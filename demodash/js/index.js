function noscript(strCode){
	var html = $(strCode.bold()); 
	html.find('script').remove();
	return html.html();
}

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