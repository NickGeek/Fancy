function getTitle() {
	if (get.site) return get.site;
	if (get.blog) return get.blog;
	return "";
}

function getType() {
	if (get.site) return "site";
	if (get.blog) return "blog";
	return "";
}

function showSnackbar(message) {
	if (get.id === '0') {
		//Handle cases when this is a new element
		window.location.href = "index.php?{0}={1}".format(getType(), getTitle());
	}

	$('#snackbar').html(message);
	$('#snackbar').addClass('show');
	setTimeout(function() { $('#snackbar').removeClass('show'); }, 1500);
}
