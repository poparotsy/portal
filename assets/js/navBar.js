
	
	$('.navbar-nav').on('click', 'li', function() {
	    $('.navbar-nav li.active').removeClass('active');
	    $(this).addClass('active');
	});