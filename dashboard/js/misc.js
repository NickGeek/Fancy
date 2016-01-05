function httpCheck(data) {
	if (data === "Not enough data has been sent") {
		alert("Not enough data has been sent");
		return false;
	}
	else if (data === "Authentication Error") {
		window.location.href = "login.html";
		return false;
	}
	else {
		return true;
	}
}