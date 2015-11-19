function noscript(strCode){
	var html = $(strCode.bold()); 
	html.find('script').remove();
	return html.html();
}

$(document).ready(function() {
	$('#md').bind('input propertychange', function() {
		$('#page').contents().find('html').html(markdown.toHTML(this.value));
		$('#htmleditor').val(markdown.toHTML(this.value));
	});

	$('#htmleditor').bind('input propertychange', function() {
		$('#page').contents().find('html').html(noscript(this.value));
		$('#md').val(toMarkdown(this.value, { gfm: true }));
	});
	
	document.getElementById("name").addEventListener("input", function() {
		var raw = $('#name').text();
		var formatted = $('<textarea />').html(raw).val();
		$('#nameInput').val(formatted);
		$('#nameCode').html(formatted);
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
	$('#page').contents().find('html').html(noscript(result.value));
	$('#md').val(toMarkdown(result.value, { gfm: true }));
}