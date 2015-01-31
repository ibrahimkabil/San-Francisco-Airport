// function for scrolling smoothly to part of page

$.fn.scrollTo = function(target, options, callback) {
	if (typeof options == 'function' && arguments.length == 2) {
		callback = options;
		options = target;
	}
	var settings = $.extend({
		scrollTarget: target,
		offsetTop: 50,
		duration: 500,
		easing: 'swing'
	}, options);
	return this.each(function() {
		var scrollPane = $(this);
		var scrollTarget = (typeof settings.scrollTarget == "number") ? settings.scrollTarget : $(settings.scrollTarget);
		var scrollY = (typeof scrollTarget == "number") ? scrollTarget : scrollTarget.offset().top + scrollPane.scrollTop() - parseInt(settings.offsetTop);
		scrollPane.animate({
			scrollTop: scrollY
		}, parseInt(settings.duration), settings.easing, function() {
			if (typeof callback == 'function') {
				callback.call(this);
			}
		});
	});
}

function cameraUpload() {
	$("#camera_upload").click();
}

function cameraUpload() {
	$("#photo").click();
}

function photoSubmit() {
	// save description during photo upload
	$("#photo_description").val($("#description").val());
	$("#photo_form").submit();
}

function save_changes() {
	$('#save').submit();
}

$(function() {

		// fade in content on page load
		$('#main').fadeIn(500);

	}

);