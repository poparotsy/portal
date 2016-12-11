$(document).ready(function() {

	/*Get the country list */

	$.ajax({

		type : "POST",
		url : "locationHandler.php",
		data : {
			'countries' : 'all'
		},
		beforeSend : function() {
			$('.countries').find("option:eq(0)").html("Please wait..");
		},

		success : function(data) {
			/*get response as json */
			$('.countries').find("option:eq(0)").html("Country...");
			var obj = jQuery.parseJSON(data);
			$(obj).each(function() {
				var option = $('<option />');
				option.attr('value', this.value).text(this.label);
				$('.countries').append(option);
			});

			/*ends */

		}
	});

	/*Get the state list */

	$('.countries').change(function() {

		var country = $(this).find('option:selected').attr("value");
		$.ajax({

			type : "POST",
			url : "locationHandler.php",
			data : {
				"country" : country
			},
			beforeSend : function() {

				$(".states option:gt(0)").remove();
				$(".cities option:gt(0)").remove();
				$('.states').find("option:eq(0)").html("Please wait..");

			},
			success : function(data) {
				/*get response as json */
				$('.states').find("option:eq(0)").html("Select State");
				var obj = jQuery.parseJSON(data);
				$(obj).each(function() {
					var option = $('<option />');
					option.attr('value', this.value).text(this.label);
					$('.states').append(option);
				});

				/*ends */

			}
		});
	});

	/*Get the city list */

	$('.states').change(function() {
		var state = $(this).find('option:selected').attr('value');
		$.ajax({
			type : "POST",
			url : "locationHandler.php",
			data : {
				"state" : state
			},
			beforeSend : function() {
				$(".cities option:gt(0)").remove();
				$('.cities').find("option:eq(0)").html("Please wait..");

			},

			success : function(data) {
				/*get response as json */
				$('.cities').find("option:eq(0)").html("Select City");

				var obj = jQuery.parseJSON(data);
				$(obj).each(function() {
					var option = $('<option />');
					option.attr('value', this.value).text(this.label);
					$('.cities').append(option);
				});

				/*ends */

			}
		});
	});

});
