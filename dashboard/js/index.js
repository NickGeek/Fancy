$(document).ready(function() {
	//Get elements and pages
	$.get("api/getElements.php", {site: get.site}).done(function(data) {
		if (!get.site) return;
		if (!httpCheck(data)) return;

		//Set to default editor
		if (localStorage.getItem('defaultEditor') == 'simple') {
			var url = 'simpleEditor.html';
		}
		else {
			var url = 'edit.html';
			localStorage.setItem('defaultEditor', 'power')
		}

		var json = JSON.parse(data);
		$('#newElement').attr('href', url+"?site="+get.site+"&id=0");
		for (var i = 0; i <= json.length - 1; i++) {
			var code = "<a class='list-group-item' href='"+url+"?site="+get.site+"&id="+json[i].id+"'>"+json[i].name+"</a>";
			$('#elementList').append(code);
		};
	}).fail(function() {
		alert("There was an error contacting the server. Please check your Internet connection.");
	});

	$.get("api/getSites.php").done(function(data) {
		if (!httpCheck(data)) return;

		var json = JSON.parse(data);

		//Redirect if no site is selected
		if (!get.site) {
			window.location.href = "index.php?site="+json[0];
			return;
		}

		for (var i = 0; i <= json.length - 1; i++) {
			var code = '<li>';
			if (json[i].toLowerCase() === get.site.toLowerCase()) code = '<li class="active">';
			code += "<a href='index.php?site="+json[i]+"'><i class='fa fa-fw fa-file'></i> "+json[i]+"</a></li>";
			$('#siteSidebar').append(code);
		};
	}).fail(function() {
		alert("There was an error contacting the server. Please check your Internet connection.");
	});

	$(document).ready(function() {
	$.get("api/getAPIVersion.php").done(function(data) {
		if (!httpCheck(data)) return;

		var updateMsg = "";
		$.get("api/updateScripts/canUpdate.php").done(function(data) {
			if (!httpCheck(data) || data === "0") return;
			updateMsg = "<br /><a href='javascript:void(0);' onclick='update({0});'>Update</a>".format(data);
		});

		$('#apiVersion').html('Fancy API v{0}{1}'.format(data, updateMsg))
	}).fail(function() {
		alert("There was an error contacting the server. Please check your Internet connection.");
	});
});
});

function newsite() {
	var name = prompt("Name of site:", "");
	if (name.length == 0) return;
	$.get("api/newsite.php", {name: name}).done(function(data) {
		if (!httpCheck(data)) return;

		window.location.href="index.php?site="+name;
	}).fail(function() {
		alert("There was an error contacting the server. Please check your Internet connection.");
	});
}

function del(name) {
	if (confirm('Are you sure you want to delete this?')) {
		$.get("api/delete.php", {type: "site", name: name}).done(function(data) {
			if (!httpCheck(data)) return;

			window.location.href="index.php";
		}).fail(function() {
			alert("There was an error contacting the server. Please check your Internet connection.");
		});
	} else {
		return;
	}
}

function changePassword() {
	var password = prompt("Enter your current password:", "");
	if (password.length == 0) return;
	var newPassword = prompt("Enter your new password:", "");
	if (newPassword.length == 0) return;

	$.post("api/changePassword.php", {password: password, newPassword: newPassword}).done(function(data) {
		if (!httpCheck(data)) return;
		
		$('.modal-title').html("Password Change")
		$("#modal-text").html(data);
		$("#modal").modal({show: true});

	}).fail(function() {
		alert("There was an error contacting the server. Please check your Internet connection.");
	});
}