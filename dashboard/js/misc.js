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
	else if (data === "Feature not in API") {
		alert("The version of the Fancy API that you are using does not support this feature");
		return false;
	}
	else {
		return true;
	}
}

// Thanks to http://stackoverflow.com/a/4673436/998467
if (!String.prototype.format) {
	String.prototype.format = function() {
		var args = arguments;
		return this.replace(/{(\d+)}/g, function(match, number) { 
			return typeof args[number] != 'undefined'
				? args[number]
				: match
			;
		});
	};
}