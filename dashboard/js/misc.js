function httpCheck(data) {
	if (data === "Not enough data has been sent") {
		alert("Not enough data has been sent");
		return false;
	}
	else if (data === "Authentication Error") {
		window.location.href = "login.html";
		return false;
	}
	else if (data === "Fancy has not been setup") {
		alert("Fancy has not been setup");
		window.location.href = "setup.html";
	}
	else {
		return true;
	}
}