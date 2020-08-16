$(document).ready(function () {
	$('.testimonial-slider').slick({
		infinite: true,
		slidesToShow: 4,
		slidesToScroll: 1,
		arrows: false,
		autoplay: true,
		autoplaySpeed: 2000,
		responsive: [
			{
				breakpoint: 1200,
				settings: {
					slidesToShow: 3
				}
			},
			{
				breakpoint: 767,
				settings: {
					slidesToShow: 2
				}
			},
			{
				breakpoint: 600,
				settings: {
					slidesToShow: 1,
					centerMode: true,
				}
			}
		]
	});
	$('.video-group .video-poster span').click(function () {
		var that = $(this).parent()
		that.siblings()[0].play();
		that.fadeOut();
		that.siblings()[0].onended = function (e) {
			that.fadeIn(500);
		}
	});
	$('.popup-btn').click(function (e) {
		e.stopPropagation();
		$('.popup-section').addClass('active');
		$('body').addClass('active-popup')
		var attr = $(this).attr('data-id');

		// For some browsers, `attr` is undefined; for others, `attr` is false. Check for both.
		if (typeof attr !== typeof undefined && attr !== false) {
			// Element has this attribute
			$('#message').val("I'm interested in learning more about " + attr + ", or candidates with similar skills and experience")
		} else {
			$('#message').val('');
		}
	});
	$('.popup-section .popup-box').click(function (e) {
		e.stopPropagation();
	});
	$('body').click(function () {
		$('.popup-section').removeClass('active');
		$('body').removeClass('active-popup');
	})
	$(document).keydown(function (e) {
		if (e.keyCode == 27) {
			$('.popup-section').removeClass('active');
			$('body').removeClass('active-popup');
		}
	});
	$('.btn-close').click(function () {
		$('.popup-section').removeClass('active');
		$('body').removeClass('active-popup');
		$('#msg-sent').remove();
	})
	
	
	$('.popup-section .btn').click(function () {
		$.post( "mail.php", { email:$('.popup-form input').val() , message: $('.popup-form textarea').val()})
		.done(function( data ) {
			//alert( "Data Loaded: " + data );
		$('.popup-form').prepend('<h3 id="msg-sent">Email Sent Successfully</h3>');
		$('.popup-form input').val('')
		$('.popup-form textarea').val('')
		});
		
	})
	$('.faq-section .btn-group a').click(function (e) {
		e.preventDefault();
		var getAccordion = $(this).attr('href');
		$(this).addClass('active');
		$(this).parent().siblings().children('a').removeClass('active');
		$(getAccordion).show();
		$(getAccordion).siblings().hide();

	})
	$('.faq-section .btn-group li:first a').addClass('active');
	$('.faq-section .result-group .accordion-section:first').show();
	$('.accordion-section .box-head').click(function () {
		$(this).parent().toggleClass('active');
		$(this).siblings().stop().slideToggle();
		$(this).parents().siblings().removeClass('active');
		$(this).parents().siblings().children('.box-body').stop().slideUp();
	})
	$('.accordion-section').each(function () {
		$(this).children('.accordion-box').eq(0).children('.box-body').slideDown();
		$(this).children('.accordion-box').eq(0).addClass('active');
	});
	$('.toggle-btn').click(function () {
		$('.navigation').toggleClass('active');
		$(this).toggleClass('active');
		$('body').toggleClass('active-menu')
	})
	$('.scroll-btn').click(function (e) {
		e.preventDefault();
		var getSection = $(this).attr('href');
		$('html').animate({ scrollTop: $(getSection).offset().top })

	})
});
