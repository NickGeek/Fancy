function login() {
	$.post("api/login.php", {password: $('input[name="password"]').val()}).done(function(data) {
		if (data != "done") {
			alert(data);
			return;
		}
		window.location.href="index.php";
	}).fail(function() {
		alert("There was an error contacting the server. Please check your Internet connection.");
	});
}

$(window).bind('keypress', function (e) {
	if (e.keyCode == 13) login();
});
