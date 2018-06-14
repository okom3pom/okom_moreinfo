$('document').ready(function(){

	if (!!$.prototype.fancybox) {
		$('a.iframe-okom-moreinfo').fancybox({
			'type' : 'iframe',
			'width':600,
			'height':600
		});
	}

	toggleFormActive();

	$('body').on('change', function(){
		toggleFormActive();
	});

	$('#moreinfo_frm').on('submit', function(e) {
		
		$('.moreinfo_waiting').show();

		$.ajax({
			url: $("#moreinfo_frm").attr("action"),
			type: "post",
			headers: {"cache-control": "no-cache"},
			async: true,
			cache: false,
			data:$("#moreinfo_frm").serialize(),
			dataType: "json",
			success: function (data) {				
				$('.moreinfo_waiting').hide();
				if (data.success == false) {
					var errors = '';
					for (var i = 0; i < data.errors.length; i++) {
						errors = errors + '<li>' + data.errors[i] + '</li>';
					}
					
					$("#moreinfo_form_error").html('<ol class="errors">'+errors+'</ol>');
				}
				else {
					$("#moreinfo_form_error").html('<p class="success">'+data.success+'</p>');
					$("#moreinfo_frm").hide();
				}				
				
			}
		});
		return false;
		
	});
	
	
});

function toggleFormActive() {
	parentForm = $('.gdprcompliancy_modules').closest('form');

	if ($('.gdprcompliancy_consent_checkbox').prop('checked') == true) {
		parentForm.find('input[type="submit"]').removeAttr('disabled');
		parentForm.find('button[type="submit"]').removeAttr('disabled');
	} else {
		parentForm.find('input[type="submit"]').attr('disabled', 'disabled');
		parentForm.find('button[type="submit"]').attr('disabled', 'disabled');
	}
}


