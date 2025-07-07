/*
Author       : Dreamstechnologies
Template Name: DreamGigs - Bootstrap Template
Version      : 1.0
*/

(function ($) {
	"use strict";

	var $slimScrolls = $('.slimscroll');

	// Stick Sidebar

	if ($(window).width() > 767) {
		if ($('.theiaStickySidebar').length > 0) {
			$('.theiaStickySidebar').theiaStickySidebar({
				// Settings
				additionalMarginTop: 30
			});
		}
	}

	var $wrapper = $('.main-wrapper');
	
	// Sidebar

	if ($(window).width() <= 991) {
		var Sidemenu = function () {
			this.$menuItem = $('.main-nav a');
		};

		function init() {
			var $this = Sidemenu;
			$('.main-nav a').on('click', function (e) {
				if ($(this).parent().hasClass('has-submenu')) {
					e.preventDefault();
				}
				if (!$(this).hasClass('submenu')) {
					$('ul', $(this).parents('ul:first')).slideUp(350);
					$('a', $(this).parents('ul:first')).removeClass('submenu');
					$(this).next('ul').slideDown(350);
					$(this).addClass('submenu');
				} else if ($(this).hasClass('submenu')) {
					$(this).removeClass('submenu');
					$(this).next('ul').slideUp(350);
				}
			});
		}

		// Sidebar Initiate
		init();
	}

	// Sticky Header
	
	$(window).scroll(function () {
		var sticky = $('.header'),
			scroll = $(window).scrollTop();
		if (scroll >= 50) sticky.addClass('fixed');
		else sticky.removeClass('fixed');
	});

	// Mobile menu sidebar overlay
	
	$('.header-fixed').append('<div class="sidebar-overlay"></div>');
	$(document).on('click', '#mobile_btn', function () {
		$('main-wrapper').toggleClass('slide-nav');
		$('.sidebar-overlay').toggleClass('opened');
		$('html').addClass('menu-opened');
		return false;
	});


	$(document).on('click', '.sidebar-overlay', function () {
		$('html').removeClass('menu-opened');
		$(this).removeClass('opened');
		$('main-wrapper').removeClass('slide-nav');
		$('#task_window').removeClass('opened');
	});

	$(document).on('click', '#menu_close', function () {
		$('html').removeClass('menu-opened');
		$('.sidebar-overlay').removeClass('opened');
		$('main-wrapper').removeClass('slide-nav');
	});

	
	// Small Sidebar

	$(document).on('click', '#toggle_btn', function () {
		if ($('body').hasClass('mini-sidebar')) {
			$('body').removeClass('mini-sidebar');
			$('.subdrop + ul').slideDown();
		} else {
			$('body').addClass('mini-sidebar');
			$('.subdrop + ul').slideUp();
		}
		return false;
	});


	$(document).on('mouseover', function (e) {
		e.stopPropagation();
		if ($('body').hasClass('mini-sidebar') && $('#toggle_btn').is(':visible')) {
			var targ = $(e.target).closest('.sidebar').length;
			if (targ) {
				$('body').addClass('expand-menu');
				$('.subdrop + ul').slideDown();
			} else {
				$('body').removeClass('expand-menu');
				$('.subdrop + ul').slideUp();
			}
			return false;
		}
	});

	// fade in scroll 

	if ($('.main-wrapper .aos').length > 0) {
		AOS.init({
			duration: 1200,
			once: true,
		});
	}

	// Mobile menu sidebar overlay

	$('body').append('<div class="sidebar-overlay"></div>');
	$(document).on('click', '#mobile_btns', function () {
		$wrapper.toggleClass('slide-nav');
		$('.sidebar-overlay').toggleClass('opened');
		$('html').toggleClass('menu-opened');
		return false;
	});

	// Sidebar
	

		// Sidebar Initiate
		$(document).on('mouseover', function(e) {
			e.stopPropagation();
			if ($('body').hasClass('mini-sidebar') && $('#toggle_btn').is(':visible')) {
				var targ = $(e.target).closest('.sidebar, .header-left, #toggle_btn').length;
				if (targ) {
					$('body').addClass('expand-menu');
					$('.subdrop + ul').slideDown();
				} else {
					$('body').removeClass('expand-menu');
					$('.subdrop + ul').slideUp();
				}
				return false;
			}
		});

	var Sidemenu = function () {
		this.$menuItem = $('#sidebar-menu a');
	};

	function initi() {
		var $this = Sidemenu;
		$('#sidebar-menu a').on('click', function (e) {
			if ($(this).parent().hasClass('submenu')) {
				e.preventDefault();
			}
			if (!$(this).hasClass('subdrop')) {
				$('ul', $(this).parents('ul:first')).slideUp(350);
				$('a', $(this).parents('ul:first')).removeClass('subdrop');
				$(this).next('ul').slideDown(350);
				$(this).addClass('subdrop');
			} else if ($(this).hasClass('subdrop')) {
				$(this).removeClass('subdrop');
				$(this).next('ul').slideUp(350);
			}
		});
		$('#sidebar-menu ul li.submenu a.active').parents('li:last').children('a:first').addClass('active').trigger('click');
	}

	// Sidebar Initiate
	initi();	


	// Sidebar Slimscroll
	if($slimScrolls.length > 0) {
		$slimScrolls.slimScroll({
			height: 'auto',
			width: '100%',
			position: 'right',
			size: '7px',
			color: '#ccc',
			wheelStep: 10,
			touchScrollStep: 100
		});
		var wHeight = $(window).height() - 60;
		$slimScrolls.height(wHeight);
		$('.sidebar .slimScrollDiv').height(wHeight);
		$(window).resize(function() {
			var rHeight = $(window).height() - 60;
			$slimScrolls.height(rHeight);
			$('.sidebar .slimScrollDiv').height(rHeight);
		});
	}

	//Gigs Card Carousel
	
	if($('.gigs-slider').length > 0) {
		$('.gigs-slider').owlCarousel({
			loop:false,
			margin:24,
			nav:true,
			dots:false,
			smartSpeed: 2000,
			autoplay:false,
			rtl: true,
			navText: [
				'<i class="fa-solid fa-chevron-left"></i>',
				'<i class="fa-solid fa-chevron-right"></i>'
			],			
			navContainer: '.worknav',
			responsive:{
				0:{
					items:1
				},				
				550:{
					items:1
				},
				768:{
					items:2
				},
				1000:{
					items:3
				}
			}
		})
	}
	
	//Gigs Card Carousel
	
	if($('.gigs-card-slider').length > 0) {
		$('.gigs-card-slider').owlCarousel({
			loop:false,
			margin:24,
			nav:true,
			dots:false,
			smartSpeed: 2000,
			autoplay:false,
			rtl: true,
			navText: [
				'<i class="fa-solid fa-chevron-left"></i>',
				'<i class="fa-solid fa-chevron-right"></i>'
			],
			responsive:{
				0:{
					items:1
				},				
				550:{
					items:1
				},
				768:{
					items:2
				},
				1000:{
					items:3
				}
			}
		})
	}

	//Card Image Carousel

	if($('.img-slider').length > 0) {
		$('.img-slider').owlCarousel({
			loop:true,
			margin:24,
			nav:false,
			dots:true,
			smartSpeed: 2000,
			autoplay:false,
			rtl: true,
			navText: [
				'<i class="fa-solid fa-chevron-left"></i>',
				'<i class="fa-solid fa-chevron-right"></i>'
			],
			responsive:{
				0:{
					items:1
				},				
				550:{
					items:1
				},
				768:{
					items:1
				},
				1000:{
					items:1
				}
			}
		})
	}

	// Clients Logo Carousel

	if($('.clients-slider').length > 0) {
		$('.clients-slider').owlCarousel({
			loop:true,
			margin:24,
			nav:false,
			dots:false,
			smartSpeed: 2000,
			autoplay:true,
			rtl: true,
			navText: [
				'<i class="fa-solid fa-chevron-left"></i>',
				'<i class="fa-solid fa-chevron-right"></i>'
			],
			responsive:{
				0:{
					items:2
				},				
				550:{
					items:3
				},
				768:{
					items:5
				},
				1000:{
					items:5
				}
			}
		})
	}

	// Popular Category Carousel

	if($('.popular-category-slider').length > 0) {
		$('.popular-category-slider').owlCarousel({
			loop:true,
			margin:24,
			nav:false,
			dots:true,
			smartSpeed: 2000,
			autoplay:false,
			rtl: true,
			navText: [
				'<i class="fa-solid fa-chevron-left"></i>',
				'<i class="fa-solid fa-chevron-right"></i>'
			],
			responsive:{
				0:{
					items:1
				},				
				550:{
					items:2
				},
				768:{
					items:3
				},
				1000:{
					items:4
				},
				1200:{
					items:5
				}
			}
		})
	}

	// Review Carousel

	if($('.review-slider').length > 0) {
		$('.review-slider').owlCarousel({
			loop:true,
			margin:24,
			nav:true,
			dots:false,
			smartSpeed: 2000,
			autoplay:false,
			rtl: true,
			navText: [
				'<i class="fa-solid fa-chevron-left"></i>',
				'<i class="fa-solid fa-chevron-right"></i>'
			],
			responsive:{
				0:{
					items:1
				},				
				550:{
					items:1
				},
				768:{
					items:2
				},
				1000:{
					items:2
				},
				1200:{
					items:3
				}
			}
		})
	}

	// Blog Carousel

	if($('.blog-carousel').length > 0) {
		$('.blog-carousel').owlCarousel({
			loop:true,
			margin:24,
			nav:false,
			dots:true,
			smartSpeed: 2000,
			autoplay:false,
			rtl: true,
			navText: [
				'<i class="fa-solid fa-chevron-left"></i>',
				'<i class="fa-solid fa-chevron-right"></i>'
			],
			responsive:{
				0:{
					items:1
				},				
				550:{
					items:1
				},
				768:{
					items:2
				},
				1000:{
					items:2
				},
				1200:{
					items:3
				}
			}
		})
	}

	// Team Carousel
	
	if($('.team-slider').length > 0) {
		$('.team-slider').owlCarousel({
			loop:false,
			margin:24,
			nav:false,
			dots:true,
			smartSpeed: 2000,
			autoplay:false,
			rtl: true,
			responsive:{
				0:{
					items:1
				},				
				550:{
					items:1
				},
				768:{
					items:2
				},
				1000:{
					items:3
				}
			}
		})
	}

	
	// CURSOR

	function mim_tm_cursor() {

		var myCursor = jQuery('.mouse-cursor');

		if (myCursor.length) {
			if ($("body")) {

				const e = document.querySelector(".cursor-inner"),
					t = document.querySelector(".cursor-outer");
				let n, i = 0,
					o = !1;
				window.onmousemove = function (s) {
					o || (t.style.transform = "translate(" + s.clientX + "px, " + s.clientY + "px)"), e.style.transform = "translate(" + s.clientX + "px, " + s.clientY + "px)", n = s.clientY, i = s.clientX
				}, $("body").on("mouseenter", "a, .cursor-pointer", function () {
					e.classList.add("cursor-hover"), t.classList.add("cursor-hover")
				}), $("body").on("mouseleave", "a, .cursor-pointer", function () {
					$(this).is("a") && $(this).closest(".cursor-pointer").length || (e.classList.remove("cursor-hover"), t.classList.remove("cursor-hover"))
				}), e.style.visibility = "visible", t.style.visibility = "visible"
			}
		}
	};
	mim_tm_cursor()

	$(window).scroll(function() { 
        var scroll = $(window).scrollTop();
        if (scroll >= 500) {
         $(".back-to-top-icon").addClass("show");
        } else {
         $(".back-to-top-icon").removeClass("show");
        }
     });

	// JQuery counterUp

	if($('.counter').length > 0) {
		$('.counter').counterUp({
			delay: 10,
			time: 2000
		  });
		$('.counter').addClass('animated fadeInDownBig');
	}	
	
	// Banner

	var TxtRotate = function(el, toRotate, period) {
        this.toRotate = toRotate;
        this.el = el;
        this.loopNum = 0;
        this.period = parseInt(period, 10) || 2000;
        this.txt = '';
        this.tick();
        this.isDeleting = false;
    }; TxtRotate.prototype.tick = function() {
        var i = this.loopNum % this.toRotate.length;
        var fullTxt = this.toRotate[i];
        if (this.isDeleting) {
        this.txt = fullTxt.substring(0, this.txt.length - 1);
        } else {
        this.txt = fullTxt.substring(0, this.txt.length + 1);
        }
        this.el.innerHTML = ' <span class = "wrap"> '+this.txt+' </span>';
        var that = this;
        var delta = 300 - Math.random() * 100;
        if (this.isDeleting) {
        delta /= 2;
        }
        if (!this.isDeleting && this.txt === fullTxt) {
        delta = this.period;
        this.isDeleting = true;
        } else if (this.isDeleting && this.txt === '') {
        this.isDeleting = false;
        this.loopNum++;
        delta = 500;
        }
        setTimeout(function() {
        that.tick();
        }, delta);
    }; window.onload = function() {
        var elements = document.getElementsByClassName('txt-rotate');
        for (var i = 0; i < elements.length; i++) {
        var toRotate = elements[i].getAttribute('data-rotate');
        var period = elements[i].getAttribute('data-period');
        if (toRotate) {
            new TxtRotate(elements[i], JSON.parse(toRotate), period);
        }
        }
        // INJECT CSS
        var css = document.createElement("style");
        css.type = "text/css";
        css.innerHTML = ".txt-rotate > .wrap { border-right: 0 }";
        document.body.appendChild(css);
    };

    
    // Loader
    
    setTimeout(function () {
        $('.loader-main');
        setTimeout(function () {
            $(".loader-main").hide();
        }, 1000);
    }, 1000);


	// Select Favourite

	$('.fav-icon').on('click', function () {
		$(this).toggleClass('favourite');
	});

	// Request Mail

	function emailcreate () {
		$.ajax({
			url:"mail.php",    //the page containing php script
			type: "post",    //request type,
			dataType : "json",
			data: $("#contact_form").serialize(),
			success:function(result){
				console.log(result);
				var messageAlert = 'alert-' + result.type;
				var messageText = result.message;
				var alertBox = '<div class="alert ' + messageAlert + ' alert-dismissable"><button type="button" class="close" data-bs-dismiss="alert" aria-hidden="true">&times;</button>' + messageText + '</div>';
				if (messageAlert && messageText) {
					console.log(alertBox);
					$('.messages').html(DOMPurify.sanitize(alertBox));
					$('#contact_form')[0].reset();
			}
		}
	});
		return false;
	}
	$('#phone-num').keyup(function () {
		if (this.value.match(/[^0-9]/g)) {
		this.value = this.value.replace(/[^0-9^-]/g, '');
		}
	});

	// Select 2

	// Select 2	
	if ($('.select2').length > 0) {
		$(".select2").select2();
   }
   
   if ($('.select').length > 0) {
	   $('.select').select2({
		   minimumResultsForSearch: -1,
		   width: '100%'
	   });
   }

	
	// Add

	if($('.delivery-add').length > 0) {
		$('.delivery-add .btn').on('click', function (e) {
			$(this).addClass("active");
			$(this).text("Added");
			$(this).prepend("<i class='feather-check'></i>");
		});
	}

	// Slick Testimonial Two

	if ($('.service-slider').length > 0) {
		$('.service-slider').slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: true,
			fade: true,
			rtl: true,
			asNavFor: '.slider-nav-thumbnails'
		});
	}

	if ($('.slider-nav-thumbnails').length > 0) {
		$('.slider-nav-thumbnails').slick({
			slidesToShow: 4,
			slidesToScroll: 1,
			asNavFor: '.service-slider',
			dots: false,
			arrows: false,
			rtl: true,
			centerMode: false,
			focusOnSelect: true

		});
	}	

	// Read More & Less
	
	if($('.more-content').length > 0) {
		$(".more-content").hide();
		$(".read-more").on("click", function() {
		 	$(this).text($(this).text() === "Read Less" ? "Read More" : "Read Less");
		 	$(".more-content").toggle(900);
		});
	}


	// recent Carousel

	if($('.recent-carousel').length > 0) {
		$('.recent-carousel').owlCarousel({
			loop:true,
			margin:24,
			nav:true,
			dots:false,
			smartSpeed: 2000,
			autoplay:false,
			rtl: true,
			navText: [
				'<i class="fa-solid fa-chevron-left"></i>',
				'<i class="fa-solid fa-chevron-right"></i>'
			],
			navContainer: '.mynav1',
			responsive:{
				0:{
					items:1
				},				
				550:{
					items:1
				},
				768:{
					items:2
				},
				1200:{
					items:3
				}
			}
		})
	}

	// recent Carousel

	if($('.service-sliders').length > 0) {
		$('.service-sliders').owlCarousel({
			loop:true,
			margin:24,
			nav:true,
			dots:false,
			smartSpeed: 2000,
			autoplay:false,
			rtl: true,
			navText: [
				'<i class="fa-solid fa-chevron-left"></i>',
				'<i class="fa-solid fa-chevron-right"></i>'
			],
			navContainer: '.service-nav',
			responsive:{
				0:{
					items:1
				},				
				600:{
					items:2
				},
				992:{
					items:3
				},
				1200:{
					items:4
				}
			}
		})
	}

	// Trending Carousel

	if($('.trend-items').length > 0) {
		$('.trend-items').owlCarousel({
			loop:true,
			margin:22,
			nav:true,
			dots:false,
			smartSpeed: 2000,
			autoplay:false,
			rtl: true,
			navText: [
				'<i class="fa-solid fa-chevron-left"></i>',
				'<i class="fa-solid fa-chevron-right"></i>'
			],
			navContainer: '.trend-nav',
			responsive:{
				0:{
					items:1
				},				
				600:{
					items:2
				},
				992:{
					items:3
				},
				1200:{
					items:4
				}
			}
		})
	}
	
	// Relate Carousel

	if($('.relate-slider').length > 0) {
		$('.relate-slider').owlCarousel({
			loop:true,
			margin:22,
			nav:false,
			dots:false,
			smartSpeed: 2000,
			autoplay:false,
			rtl: true,
			responsive:{
				0:{
					items:1
				},				
				600:{
					items:2
				},
				1200:{
					items:3
				}
			}
		})
	}

	// Testimonial Carousel

	if($('.testimonial-slider').length > 0) {
		$('.testimonial-slider').owlCarousel({
			loop:true,
			margin:22,
			nav:true,
			dots:false,
			smartSpeed: 2000,
			autoplay:false,
			rtl: true,
			navText: [
				'<i class="fa-solid fa-chevron-left"></i>',
				'<i class="fa-solid fa-chevron-right"></i>'
			],
			responsive:{
				0:{
					items:1
				},				
				600:{
					items:2
				},
				1200:{
					items:3
				}
			}
		})
	}

	// Testimonial Carousel

	if($('.testimonials-slider').length > 0) {
		$('.testimonials-slider').owlCarousel({
			loop:true,
			margin:22,
			nav:false,
			dots:true,
			smartSpeed: 2000,
			autoplay:false,
			rtl: true,
			navText: [
				'<i class="fa-solid fa-chevron-left"></i>',
				'<i class="fa-solid fa-chevron-right"></i>'
			],
			responsive:{
				0:{
					items:1
				},				
				600:{
					items:2
				},
				1200:{
					items:3
				}
			}
		})
	}

	// Profile Carousel

	if($('.profile-slider').length > 0) {
		$('.profile-slider').owlCarousel({
			loop:true,
			margin:22,
			nav:true,
			dots:false,
			smartSpeed: 2000,
			autoplay:false,
			navText: [
				'<i class="fa-solid fa-chevron-left"></i>',
				'<i class="fa-solid fa-chevron-right"></i>'
			],
			responsive:{
				0:{
					items:1
				},				
				600:{
					items:2
				},
				1200:{
					items:3
				}
			}
		})
	}

	// Works Carousel
	
	if($('.works-slider').length > 0) {
		$('.works-slider').owlCarousel({
			loop:true,
			margin:22,
			nav:true,
			dots:false,
			smartSpeed: 2000,
			autoplay:false,
			navText: [
				'<i class="fa-solid fa-chevron-left"></i>',
				'<i class="fa-solid fa-chevron-right"></i>'
			],
			responsive:{
				0:{
					items:1
				},				
				600:{
					items:2
				},
				1200:{
					items:3
				}
			}
		})
	}

	// Login Carousel

	if($('.login-carousel').length > 0) {
		$('.login-carousel').owlCarousel({
			loop:true,
			margin:24,
			nav: false,
			dots:true,
			smartSpeed: 2000,
			autoplay:false,
			rtl:true,
			responsive:{
				0:{
					items:1
				}
			}
		})
	}

	// View all Show hide One

	if($('.viewall-one').length > 0) {
		$(".viewall-one").hide();
		$(".viewall-button-one").on("click", function() {
	 		$(this).text($(this).text() === "Less Categories" ? "More 20+ Categories" : "Less Categories");
	 		$(".viewall-one").slideToggle(900);
		});	  	
	}

	// View all Show hide One

	if($('.viewall-location').length > 0) {
		$(".viewall-location").hide();
		$(".viewall-btn-location").on("click", function() {
	 		 $(this).text($(this).text() === "Less Locations" ? "More 20+ Locations" : "Less Locations");
	 		 $(".viewall-location").slideToggle(900);
		});	  	
	}

	// Filter Select
	
	if($('.filters-wrap').length > 0) {
		var show = true;
		$('.filter-header a').on("click", function() {
			if (show) {
				$(this).closest(".collapse-card").children(".collapse-body").css("display","block");
				$(this).closest(".collapse-card").addClass('active');
				show = false;
			} else {
				$(".collapse-body").css("display","none");
				$(this).closest(".collapse-card").removeClass('active');
				show = true;
			}
		});
		
	}


	if($('.yearpicker').length > 0 ){
		$('.yearpicker').datetimepicker({
			viewMode: 'years',
			format: 'YYYY',

			icons: {
				up: "fas fa-angle-up",
				down: "fas fa-angle-down",
				next: 'fas fa-angle-right',
				previous: 'fas fa-angle-left'
			}
		});
	}

		// Mobile menu sidebar overlay
		$(document).ready(function () {
			var $wrapper = $('.main-wrapper'); // Define the wrapper for layout
		
			// Append sidebar overlay if it doesn't already exist
			if (!$('.sidebar-overlay').length) {
				$('body').append('<div class="sidebar-overlay"></div>');
			}
		
			// Open Sidebar on Mobile Button Click
			$(document).on('click', '#mobile_btn', function () {
				$wrapper.addClass('slide-nav'); // Open sidebar
				$('.sidebar-overlay').addClass('opened'); // Show overlay
				$('html').addClass('menu-opened'); // Prevent background scrolling
				$('#task_window').removeClass('opened'); // Ensure task window is closed
				return false;
			});
		
			// Close Sidebar when clicking the overlay
			$(document).on('click', '.sidebar-overlay', function () {
				closeSidebar();
			});
		
			// Close Sidebar when clicking Close Button
			$(document).on('click', '#menu_close', function () {
				closeSidebar();
			});
		
			// Function to close the sidebar properly
			function closeSidebar() {
				$wrapper.removeClass('slide-nav'); // Hide sidebar
				$('.sidebar-overlay').removeClass('opened'); // Hide overlay
				$('html').removeClass('menu-opened'); // Allow scrolling again
				$('#task_window').removeClass('opened'); // Ensure task window is closed
			}
		});
		
	// More & Less
	
	if($('.more-content').length > 0) {
		$(".more-content").hide();
		$(".show-more").on("click", function() {
	 		 $(this).text($(this).text() === "Show Less" ? "Show More" : "Show Less");
	 		 $(".more-content").toggle(900);
		});	  	
	}

	// Password Eye

	if ($('.toggle-password').length > 0) {
		$(document).on('click', '.toggle-password', function () {
			$(this).toggleClass("feather-eye feather-eye-off");
			var input = $(".pass-input");
			if (input.attr("type") === "password") {
				input.attr("type", "text");
			} else {
				input.attr("type", "password");
			}
		});
	}

	if ($('.toggle-password-confirm').length > 0) {
		$(document).on('click', '.toggle-password-confirm', function () {
			$(this).toggleClass("feather-eye feather-eye-off");
			var input = $(".pass-confirm");
			if (input.attr("type") === "password") {
				input.attr("type", "text");
			} else {
				input.attr("type", "password");
			}
		});
	}

	// Floating Label

	if($('.floating').length > 0 ){
		$('.floating').on('focus blur', function (e) {
		$(this).parents('.form-focus').toggleClass('focused', (e.type === 'focus' || this.value.length > 0));
		}).trigger('blur');
	}

	// Tooltip

	if($('[data-bs-toggle="tooltip"]').length > 0) {
		var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
		var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
			return new bootstrap.Tooltip(tooltipTriggerEl)
		})
	}

	// Input Enable & Disable 

	$(".extra-serv .checkmark").on("click", function(){
		var $listSort = $('.exta-label');
		if ($listSort.attr('disabled')) {
        	$listSort.removeAttr('disabled');
    	} else {
        $listSort.attr('disabled','disabled');
    }
    });
	
	// Coming Soon
    
    if($('.days-count').length > 0) {
        // Get html elements
        let day = document.querySelector('.days');
        let hour = document.querySelector('.hours');
        let minute = document.querySelector('.minutes');
        let second = document.querySelector('.seconds');

        function setCountdown() {

        // Set countdown date
        let countdownDate = new Date('sep 27, 2025 16:00:00').getTime();

        // Update countdown every second
        let updateCount = setInterval(function(){

            // Get today's date and time
            let todayDate = new Date().getTime();

            // Get distance between now and countdown date
            let distance = countdownDate - todayDate;

            let days = Math.floor(distance / (1000 * 60 * 60 *24));

            let hours = Math.floor(distance % (1000 * 60 * 60 *24) / (1000 * 60 *60));

            let minutes = Math.floor(distance % (1000 * 60 * 60 ) / (1000 * 60));

            let seconds = Math.floor(distance % (1000 * 60) / 1000);

            // Display values in html elements
            day.textContent = days;
            hour.textContent = hours;
            minute.textContent = minutes;
            second.textContent = seconds;

            // if countdown expires
            if(distance < 0){
                clearInterval(updateCount);
                document.querySelector(".days-count").innerHTML = '<h1>EXPIRED</h1>'
            }
        }, 1000)
        }

        setCountdown()
    }

    // Add Sign
	$(document).on('click', '.trash-sign', function () {
		$(this).closest('.sign-cont').remove();
		return false;
	});
	$(document).on('click','.amount-add',function(){

			var signcontent = `<div class="row sign-cont">
			<div class="col-md-8">
				<div class="form-wrap">
					<label class="col-form-label">Earn Extra  Money  - Offer Optional Add-on Services For Buyer<span class="text-danger ms-1">*</span></label>
					<input type="text" class="form-control">
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-wrap">
					<label class="col-form-label">For ($)</label>
					<input type="text" class="form-control">
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-wrap">
				<label class="col-form-label">Days</label>
				 <div class="d-flex align-items-center">
				  <div>					
					<input type="text" class="form-control">
				   </div>
					<a href="javascript:void(0);" class="trash-sign ms-2 text-danger"><i class="feather-trash-2"></i></a>
				  </div>
				</div>
			</div>
		</div>`;
		$(".add-content").append(signcontent);
		return false;
	});

	// Datatable

	if($('.datatable').length > 0) {
		$('.datatable').DataTable({
			"bFilter": true,
			"bLengthChange": false,
			"bInfo": true,
			"ordering": false,
			"language": {
				search: ' ',
				searchPlaceholder: "Search",
                paginate: {
                    next: ' <i class=" fa fa-angle-right"></i>',
                    previous: '<i class="fa fa-angle-left"></i> '
                },
             },
			initComplete: (settings, json)=>{
                $('.dt-paging').appendTo('#tablepage');
                $('.dt-search').appendTo('#tablefilter');
                $('.dataTables_info').appendTo('#tableinfo');
            },                
		});		
	}

	// Horizontal Slide
document.addEventListener("DOMContentLoaded", function () {
	const scrollers = document.querySelectorAll(".horizontal-slide");
	scrollers.forEach((scroller) => {
		scroller.setAttribute("data-animated", true);
		const scrollerInner = scroller.querySelector(".slide-list");
		const scrollerContent = Array.from(scrollerInner.children);
		scrollerContent.forEach((item) => {
			const duplicatedItem = item.cloneNode(true);
			duplicatedItem.setAttribute("aria-hidden", true);
			scrollerInner.appendChild(duplicatedItem);
		});
	});
});


document.addEventListener("DOMContentLoaded", function () {
    // Select all collapsible elements
    document.querySelectorAll(".card-collapse").forEach(function (collapseEl) {
        collapseEl.addEventListener("show.bs.collapse", function () {
            // Add 'active' class to parent .faq-card when expanded
            this.closest(".faq-card").classList.add("active");
        });

        collapseEl.addEventListener("hide.bs.collapse", function () {
            // Remove 'active' class when collapsed
            this.closest(".faq-card").classList.remove("active");
        });
    });
});

	// Date Time Picker
	
	if($('.datetimepicker').length > 0) {
		$('.datetimepicker').datetimepicker({
			format: 'DD-MM-YYYY',
			icons: {
				up: "fa fa-angle-up",
				down: "fa-solid fa-angle-down",
				next: 'fa-solid fa-angle-right',
				previous: 'fa-solid fa-angle-left'
			}
		});
	}

	//Top Online Contacts
	if($('.top-online-contacts .swiper-container').length > 0 ){
		var swiper = new Swiper('.top-online-contacts .swiper-container', {
			slidesPerView: 5,
			spaceBetween: 15,
		});
	}

	// PHONE 

	if ($('#phone').length > 0) {
		var input = document.querySelector("#phone");
		window.intlTelInput(input, {
			utilsScript: "assets/plugins/intltelinput/js/utils.js",
		});
	}

	if ($('#phone2').length > 0) {
		var input = document.querySelector("#phone2");
		window.intlTelInput(input, {
			utilsScript: "assets/plugins/intltelinput/js/utils.js",
		});
	}

	// Chat Search Visible

	$('.user-chat-search-btn').on('click', function () {
		$('.user-chat-search').addClass('visible-chat');
	});
	$('.user-close-btn-chat').on('click', function () {
		$('.user-chat-search').removeClass('visible-chat');
	});

	// Chat Search Visible

	$('.chat-search-btn').on('click', function () {
		$('.chat-search').addClass('visible-chat');
	});
	$('.close-btn-chat').on('click', function () {
		$('.chat-search').removeClass('visible-chat');
	});
	$(".chat-search .form-control").on("keyup", function() {
		var value = $(this).val().toLowerCase();
		$(".chat .chat-body .messages .chats").filter(function() {
		  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
	});

	$(".user-list-item:not(body.status-page .user-list-item, body.voice-call-page .user-list-item)").on('click', function () {
		if ($(window).width() < 992) {
			$('.left-sidebar').addClass('hide-left-sidebar');
			$('.chat').addClass('show-chatbar');
		}
	});
	
	$(".left_sides").on('click', function () {
		if ($(window).width() <= 991) {
			$('.sidebar-group').removeClass('hide-left-sidebar');
			$('.sidebar-menu').removeClass('d-none');
		}
	});
	$(".left_sides").on('click', function () {
		if ($(window).width() <= 991) {
			$('.chat-messages').removeClass('show-chatbar');
		}
	});
	$(".user-list li a").on('click', function () {
		if ($(window).width() <= 767) {
			$('.left-sidebar').addClass('hide-left-sidebar');
				$('.sidebar-menu').addClass('d-none');
		}
	});


	const $menu = $('.dropdowns')

		const onMouseUp = e => {
		if (!$menu.is(e.target) // If the target of the click isn't the container...
		&& $menu.has(e.target).length === 0) // ... or a descendant of the container.
		{
			$menu.removeClass('is-active')
		}
		}

		$('.toggle').on('click', () => {
		$menu.toggleClass('is-active').promise().done(() => {
			if ($menu.hasClass('is-active')) {
			$(document).on('mouseup', onMouseUp) // Only listen for mouseup when menu is active...
			} else {
			$(document).off('mouseup', onMouseUp) // else remove listener.
			}
		})
	})

	// Date Range Picker
	if($('#reportrange').length > 0) {
		var start = moment().subtract(29, "days"),
			end = moment();

		function report_range(start, end) {
			$("#reportrange span").html(start.format("D MMM YY") + " - " + end.format("D MMM YY"))
		}
		$("#reportrange").daterangepicker({
			startDate: start,
			endDate: end,
			ranges: {
				'Today': [moment(), moment()],
				'Yesterday': [moment().subtract(1, "days"), moment().subtract(1, "days")],
				"Last 7 Days": [moment().subtract(6, "days"), moment()],
				"Last 30 Days": [moment().subtract(29, "days"), moment()],
				"This Month": [moment().startOf("month"), moment().endOf("month")],
				"Last Month": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
			}
		}, report_range), report_range(end, end);
	}
		   
	
})(jQuery);



	

