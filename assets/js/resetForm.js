$(document).ready(function() {
	// Captcha
	
	  function randomNumber(min, max) {
	        return Math.floor(Math.random() * (max - min + 1) + min);
	    }

	    function generateCaptcha() {
	        $('#captchaOperation').html([randomNumber(1, 10), '+', randomNumber(1, 20), '='].join(' '));
	    }

	    generateCaptcha();
	// Captcha
	    


$('#resetForm').formValidation({

        message: 'This value is not valid',
        framework: 'bootstrap',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
         
            resetEmail: {
                validators: {
                	notEmpty: {},
                    emailAddress: {},
                	remote: {
                    	url: 'validate.php',
                    	type: 'POST',             
                    	delay: 2000,                 
                    message: 'Email is not recognized.'
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
$('#resetForm').on('err.form.fv', function(e) {
    // Regenerate the captcha
    generateCaptcha();
});


});

