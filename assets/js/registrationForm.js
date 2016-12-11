$(document).ready(function() {
	// Captcha
	
	  function randomNumber(min, max) {
	        return Math.floor(Math.random() * (max - min + 1) + min);
	    }

	    function generateCaptcha() {
	        $('#captchaOperation').html([randomNumber(1, 11), '+', randomNumber(6, 69), '='].join(' '));
	    }

	    generateCaptcha();
	// Captcha
	
    FormValidation.Validator.securePassword = {
        validate: function(validator, $field, options) {
            var value = $field.val();
            if (value === '') {
                return true;
            }

            // Check the password strength
            if (value.length < 8) {
                return {
                    valid: false,
                    message: 'The password must be more than 8 characters long'
                };
            }

            // The password doesn't contain any uppercase character
            if (value === value.toLowerCase()) {
                return {
                    valid: false,
                    message: 'The password must contain at least one upper case character'
                }
            }

            // The password doesn't contain any uppercase character
            if (value === value.toUpperCase()) {
                return {
                    valid: false,
                    message: 'The password must contain at least one lower case character'
                }
            }

            // The password doesn't contain any digit
            if (value.search(/[0-9]/) < 0) {
                return {
                    valid: false,
                    message: 'The password must contain at least one digit'
                }
            }

            return true;
        }
    };


$('#registrationForm').formValidation({
        message: 'This value is not valid',
        framework: 'bootstrap',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            firstName: {
                validators: {
                    notEmpty: {},
                    stringCase: {
                        'case': 'upper'
                         
                 
                    	
                    }
                }
            },
            lastName: {
                validators: {
                    notEmpty: {},
                    stringCase: {
                        'case': 'upper'
                    }
                }
            },
            username: {
                message: 'The username is not valid',
                validators: {
                    notEmpty: {},
                    stringLength: {
                        min: 6,
                        max: 20
                    },
                    regexp: {
                        regexp: /^[a-zA-Z0-9_\.]+$/
                    },
                }
            },
            email: {
                validators: {
                	notEmpty: {
                		message: 'The email address is required and can\'t be empty'
                	},
                	
                   emailAddress: {
                	   
                   },
                    				
                   	regexp: {
                        regexp: '^[^@\\s]+@([^@\\s]+\\.)+[^@\\s]+$',
                        message: 'The value is not a valid email address'
                    },
                	
                  remote: {
                  	url: 'validate.php',
                	type: 'POST',             
                	delay: 3000,                 
                	message: 'Invalid or already in use.'
                  }
                }
            },
            street: {
            	validators: {
            		
            	}
            },
            postalCode: {
            	validators: {
            		
            	}
            },
            country: {
                icon: false,
                validators: {
                	notEmpty: {
                		message: 'Country field is required.'
                    	},
                    
                }
            },
            password: {
                validators: {
                    notEmpty: {},
                    stringLength: {
                        min: 8,
                        max: 100
                    },

                    securePassword: {
                        message: 'The password is not valid'
                    },

                    
                    different: {
                        field: 'username',
                        message: 'The password cannot be the same as username'
                    }
                }
            },
            passwordConfirm: {
                validators: {
                    notEmpty: {},
                    identical: {
                        field: 'password',
                        message: 'The password and its confirm are not the same'
                    }
                }
            },
            phoneCell: {
                validators: {
                	
                	  callback: {
                          message: 'The phone number is not valid',
                          callback: function(value, validator, $field) {
                              return value === '' || $field.intlTelInput('isValidNumber');
                          }
                      },
                    notEmpty: {},
                                    
                }
            },
            phoneOther: {
                icon: false,
                validators: {
                     phone: {}
                }
            },
            // Captcha
            captcha: {
                validators: {
                    callback: {
                        message: 'Wrong answer',
                        callback: function(value, validator, $field) {
                            var items = $('#captchaOperation').html().split(' '),
                                sum   = parseInt(items[0]) + parseInt(items[2]);
                            return value == sum;
                        }
                    }
                }
            }
            // Captcha
        } 
    }).on('err.validator.fv', function(e, data) {
        // $(e.target)    --> The field element
        // data.fv        --> The FormValidation instance
        // data.field     --> The field name
        // data.element   --> The field element
        // data.validator --> The current validator name

        data.element
            .data('fv.messages')
            // Hide all the messages
            .find('.help-block[data-fv-for="' + data.field + '"]').hide()
            // Show only message associated with current validator
            .filter('[data-fv-validator="' + data.validator + '"]').show();
    });
   
/*
 * 
 */

$('#registrationForm').find('[name="phoneCell"]')
    .intlTelInput({
        utilsScript: 'assets/js/utils.js',
        autoPlaceholder: true,
        autoFormat: true,
        autoHideDialCode: false,
        initialCountry: "auto",
        nationalMode: false,
        numberType: "MOBILE",
        dropdownContainer: $("body"),
        geoIpLookup: function(callback) {
            $.get('http://ipinfo.io', function() {}, "jsonp").always(function(resp) {
              var countryCode = (resp && resp.country) ? resp.country : "";
              callback(countryCode);
            });
          },
        preferredCountries: ['ca', 'us', 'gb']
    });


$('#phoneCell').on('click', '.country-list', function() {
    $('#registrationForm').formValidation('revalidateField', 'phoneCell');
});


/*
 * Captcha
 */

$('#registrationForm').on('err.form.fv', function(e) {
    // Regenerate the captcha
    generateCaptcha();
});

/*
 * show only one error message. NOT WORKIG SO FAR.
 */
$('registrationFrom').on('err.validator.fv', function(e, data) {
    // $(e.target)    --> The field element
    // data.fv        --> The FormValidation instance
    // data.field     --> The field name
    // data.element   --> The field element
    // data.validator --> The current validator name

    data.element
        .data('fv.messages')
        // Hide all the messages
        .find('.help-block[data-fv-for="' + data.field + '"]').hide()
        // Show only message associated with current validator
        .filter('[data-fv-validator="' + data.validator + '"]').show();
});




/*
 * Captcha
 */

/*
 * 
 */


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


/*
 * 
 */

 });

/*
 * 
 * 
 */
