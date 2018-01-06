// Thanks to http://stackoverflow.com/a/4673436/998467
String.prototype.format||(String.prototype.format=function(){var a=arguments;return this.replace(/{(\d+)}/g,function(b,c){return"undefined"!=typeof a[c]?a[c]:b})});

//Thanks to http://stackoverflow.com/a/32002858/998467
var get=function(){function a(a){return decodeURIComponent((a+"").replace(/\+/g,"%20"))}function b(b){for(var c={},d=b.split("&"),e=0;e<d.length;e++){var f=d[e].split("=");c[f[0]]=a(f[1])}return c}var c=window.location.search.substr(1);return null!=c&&""!=c?b(c):{}}();

function httpCheck(a){return"Not enough data has been sent"===a?(alert("Not enough data has been sent"),!1):"Authentication Error"===a?(window.location.href="login.html",!1):"Fancy has not been setup"!==a?"Feature not in API"!==a||(alert("The version of the Fancy API that you are using does not support this feature"),!1):(alert("Fancy has not been setup"),void(window.location.href="setup.html"))}

function reqGET(url, callback) {
	var xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4) {
			callback(xhr.responseText, xhr.status);
		}
	}
	xhr.open("GET", url, true);
	xhr.setRequestHeader("Cache-Control", "max-age=0");
	xhr.send();
}

function fail() {
	alert("There was an error contacting the server. Please check your Internet connection.");
}

function safeElement(element) {
	return element || {innerHTML: "", style: {display: ""}};
}

function displayPost(post, domPost) {
	//Copy the template into the post
	domPost.innerHTML = document.querySelector('[ftemplate="post"]').innerHTML;

	//Fill out the template
	safeElement(domPost.querySelector('[ftemplate="title"]')).innerHTML = post.title;
	safeElement(domPost.querySelector('[ftemplate="timestamp"]')).innerHTML = post.timestamp;
	safeElement(domPost.querySelector('[ftemplate="content"]')).innerHTML = post.html;
}

function removeOpenPTag(html) {
	function shouldFix() {
		return html.toLowerCase().substring(0, 3) === '<p>'
			&& safeElement(document.querySelector('[ftemplate="content"]')).tagName.toLowerCase() === '<p>';
	}

	return shouldFix() ? html.substring(3, html.length - 4) : html;
}

document.addEventListener("DOMContentLoaded", function() {
	var container = document.querySelector('[ftemplate="container"]');

	safeElement(document.querySelector('[ftemplate="post"]')).style.display = 'none';

	safeElement(document.querySelector('[ftemplate="comments"]')).style.display = 'none';

	if (get.view) {
		safeElement(document.querySelector('[ftemplate="comments"]')).style.display = 'inherit';

		reqGET("{0}{1}?fancy_getPost={2}".format(document.location.origin, document.location.pathname, get.view), function(data, code) {
			if (code !== 200) return fail();
			
			if (!httpCheck(data)) return;
			var post = JSON.parse(data);
			post.html = removeOpenPTag(post.html);

			container.innerHTML += '<article id="post{0}" class="fancy-post"></article>'.format(post.id);
			displayPost(post, document.getElementById("post"+post.id));
		});
	} else {
		reqGET("{0}{1}?fancy_getPosts=1".format(document.location.origin, document.location.pathname), function(data, code) {
			if (code !== 200) return fail();
			if (!httpCheck(data)) return;
			
			var json = JSON.parse(data);

			for (var i = json.length-1; i >= 0; i--) {
				var post = json[i];
				post.html = removeOpenPTag(post.html);
				post.html = '{0}&hellip; <a href="?view={1}">Read More >></a></p>'.format(post.html.split('</p>')[0], post.id);
				
				container.innerHTML += '<article id="post{0}" class="fancy-post"></article>'.format(post.id);
				displayPost(post, document.getElementById("post"+post.id));
			};
		});
	}
});
