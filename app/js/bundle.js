$(document).ready(function(){$(".testimonial-slider").slick({infinite:!0,slidesToShow:4,slidesToScroll:1,arrows:!1,autoplay:!0,autoplaySpeed:2e3,responsive:[{breakpoint:1200,settings:{slidesToShow:3}},{breakpoint:767,settings:{slidesToShow:2}},{breakpoint:600,settings:{slidesToShow:1,centerMode:!0}}]}),$(".video-group .video-poster span").click(function(){var e=$(this).parent();e.siblings()[0].play(),e.fadeOut(),e.siblings()[0].onended=function(i){e.fadeIn(500)}}),$(".popup-btn").click(function(i){i.stopPropagation(),$(".popup-section").addClass("active"),$("body").addClass("active-popup");var e=$(this).attr("data-id");void 0!==e&&!1!==e?$("#message").val("I'm interested in learning more about "+e+", or candidates with similar skills and experience"):$("#message").val("")}),$(".popup-section .popup-box").click(function(i){i.stopPropagation()}),$("body").click(function(){$(".popup-section").removeClass("active"),$("body").removeClass("active-popup")}),$(document).keydown(function(i){27==i.keyCode&&($(".popup-section").removeClass("active"),$("body").removeClass("active-popup"))}),$(".btn-close").click(function(){$(".popup-section").removeClass("active"),$("body").removeClass("active-popup")}),$(".faq-section .btn-group a").click(function(i){i.preventDefault();var e=$(this).attr("href");$(this).addClass("active"),$(this).parent().siblings().children("a").removeClass("active"),$(e).show(),$(e).siblings().hide()}),$(".faq-section .btn-group li:first a").addClass("active"),$(".faq-section .result-group .accordion-section:first").show(),$(".accordion-section .box-head").click(function(){$(this).parent().toggleClass("active"),$(this).siblings().stop().slideToggle(),$(this).parents().siblings().removeClass("active"),$(this).parents().siblings().children(".box-body").stop().slideUp()}),$(".accordion-section").each(function(){$(this).children(".accordion-box").eq(0).children(".box-body").slideDown(),$(this).children(".accordion-box").eq(0).addClass("active")}),$(".toggle-btn").click(function(){$(".navigation").toggleClass("active"),$(this).toggleClass("active"),$("body").toggleClass("active-menu")}),$(".scroll-btn").click(function(i){i.preventDefault();var e=$(this).attr("href");$("html").animate({scrollTop:$(e).offset().top})})});