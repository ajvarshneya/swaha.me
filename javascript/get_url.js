$("document").ready(function(){
	$("#js-submit-url").submit(function(){

		$('#js-copy-button:hidden').fadeIn("slow");
		$('#js-copy-button').css('display', 'block');

		var data = {
	    };
	    data = $(this).serialize() + "&" + $.param(data);

		$.ajax({
			type: "POST",
			dataType: "json",
			url: "../php/get_surl.php",
			data: data,
			success: function(data) {
				$('#url-form').val("http://swaha.me/" + data["surl"]);
			}
		});

	return false;
	});
});