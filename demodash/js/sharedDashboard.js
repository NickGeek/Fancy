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
