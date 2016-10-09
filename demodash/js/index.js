$(document).ready(function() {
	//Get elements and pages + blogs/posts
	if (get.site) {
		$('#websiteInstructions').show();

		$.get("api/getElements.php", {site: get.site}).done(function(data) {
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
	}
	else if (get.blog) {
		$('#blogInstructions').show();

		$.get("api/getPosts.php", {blog: get.blog}).done(function(data) {
			if (!httpCheck(data)) return;

			//Set to default editor
			if (localStorage.getItem('defaultEditor') == 'simple') {
				var url = 'simpleEditor.html';
			}
			else {
				var url = 'edit.html';
				localStorage.setItem('defaultEditor', 'power')
			}

			$('#elementList').hide();
			$('#postList').show();

			var json = JSON.parse(data);
			$('#newPost').attr('href', url+"?blog="+get.blog+"&id=0");
			for (var i = json.length - 1; i >= 0; i--) {
				var code = "<a class='list-group-item' href='{0}?blog={1}&id={2}'>{3} <em>{4}</em></a>".format(url, get.blog, json[i].id, json[i].title, json[i].timestamp);
				$('#postList').append(code);
			};
		}).fail(function() {
			alert("There was an error contacting the server. Please check your Internet connection.");
		});
	}

	$.get("api/getSites.php").done(function(data) {
		if (!httpCheck(data)) return;

		var json = JSON.parse(data);

		//Redirect if no site is selected
		if (!get.site && !get.blog) {
			window.location.href = "index.php?site="+json[0];
			return;
		}

		for (var i = 0; i <= json.length - 1; i++) {
			var code = '<li>';
			if (get.site && json[i].toLowerCase() === get.site.toLowerCase()) code = '<li class="active">';
			code += "<a href='index.php?site="+json[i]+"'><i class='fa fa-fw fa-file'></i> "+json[i]+"</a></li>";
			$('#addBlogButton').before(code);
		};
	}).fail(function() {
		alert("There was an error contacting the server. Please check your Internet connection.");
	}).always(function() {

	});

	$.get("api/getBlogs.php").done(function(data) {
		if (data === "Feature not in API") {
			$('#addBlogButton').hide();
			return;
		}
		if (!httpCheck(data)) return;

		var json = JSON.parse(data);

		//Redirect if no blog is selected
		// if (!get.site && !get.blog && json[0]) {
		// 	window.location.href = "index.php?blog="+json[0];
		// 	return;
		// }

		for (var i = 0; i <= json.length - 1; i++) {
			var code = '<li>';
			if (get.blog && json[i].toLowerCase() === get.blog.toLowerCase()) code = '<li class="active">';
			code += "<a href='index.php?blog="+json[i]+"'><i class='fa fa-fw fa-newspaper-o'></i> "+json[i]+"</a></li>";
			$('#siteSidebar').append(code);
		};
	}).fail(function() {
		alert("There was an error contacting the server. Please check your Internet connection.");
	});

	$(document).ready(function() {
	$.get("api/getVersions.php").done(function(data) {
		if (!httpCheck(data)) return;

		var updateMsg = "";
		$.get("api/updateScripts/canUpdate.php").done(function(data) {
			if (!httpCheck(data) || data === "0") return;
			updateMsg = "<br /><a href='javascript:void(0);' onclick='update(\"{0}\");'>Update</a>".format(data);
		}).always(function() {
			var json = JSON.parse(data);
			$('#fancyVersion').html('Supports Fancy v{0}'.format(json.fancy))
			$('#apiVersion').html('Fancy API v{0}{1}'.format(json.api, updateMsg))
		});
	}).fail(function() {
		alert("There was an error contacting the server. Please check your Internet connection.");
	});

	var t = "false"; if (get.blog) t = "true";
	$('#pageTitle').html('{0} <a href="javascript:void(0);" class="btn btn-danger" onclick="del(\'{0}\', {1});">Delete</a>'.format(getTitle(), t));
});
});
function newsite() {
	return;
}

function del(name) {
	return;
}

function changePassword() {
	return;
}

function update(url) {
	return;
}
