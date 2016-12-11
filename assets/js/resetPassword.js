$(document).ready(function() {
	// Captcha
	
	  function randomNumber(min, max) {
	        return Math.floor(Math.random() * (max - min + 1) + min);
	    }

	    function generateCaptcha() {
	        $('#captchaOperation').html([randomNumber(1, 10), '+', randomNumber(1, 20), '='].join(' '));
	    }

	    generateCaptcha();
	    
	    
	    
	    /*
	     * 
	     */
	    
	    function getUrlVars() {
	    	var vars = {};
	    	var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
	    	vars[key] = value;
	    	});
	    	return vars;
	    	}
	    
	    /*
	     * 
	     */
	    
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

var resetKey = getUrlVars()["key"];


$('#resetPasswordForm').formValidation({

	     message: 'This value is not valid',
        framework: 'bootstrap',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
        	
        	key: {
        		validator: {
        		notEmpty: {
        			message: 'Please use the link sent via email. <a href ="/portal/reset.php">click here</a> to resend the link.'	
        			}
        		}
        	},
      
            resetPasswordEmail: {
            	  validators: {
                	notEmpty: {
                		
                	},
                    
                	remote: {
                    	url: 'validate.php',
                    	data: function(validator, $field, value) {
                            return { 
                            	key: validator.getFieldElements('key').val()
                            		};
                               },
                    	type: 'POST',             
                    	delay: 2000,                 
                    message: 'Email is not recognized. Make sure to use the link from the reset password email and the same email address, <a href ="/portal/reset.php">click here</a> to resend the link.'
                  }                  
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
    
	});
$('#resetPasswordForm').on('err.form.fv', function(e) {
    // Regenerate the captcha
    generateCaptcha();
});


});

