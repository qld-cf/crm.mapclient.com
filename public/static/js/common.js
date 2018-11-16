function form_submit(obj) {
	$(obj).ajaxSubmit(function(json) {
		data = JSON.parse(json);
		callback_submit(data);
	});

	return false;
}