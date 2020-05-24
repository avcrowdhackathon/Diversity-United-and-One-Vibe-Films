(function($){
"use strict";

// Custom styles
var styleElems = $('.custom-styles'), count = styleElems.length;
var customStyles = '';
styleElems.each( function(i) {
	customStyles += $(this).data('styles');
	if (!--count) injectStyles(customStyles);
	$(this).remove();
});

function injectStyles(styles) {
	$('head').append('<style type="text/css">'+styles+'</style>');
}

function initBlazy() {
	window.bLazy = new Blazy({
		offset: 0,
		success: function(element){
			var parent = element.parentNode;
			parent.className = parent.className.replace(/\bloading\b/,'');
			parent.className += ' loaded';
			$(window).trigger('masonryLayout');
			setTimeout(function(){
				$(element).parents('.hover-not-ready').removeClass('hover-not-ready')	
			}, 700);
		},
		error: function(el, msg) {
			console.log(msg);
		}
	});
}

function destroyBlazy() {
	window.bLazy.destroy();
}

// Full Pages
function showRowsFullPages() {

	$('.veso-full-pages').each(function(){
		var $section = $(this),
			$column = $section.find('> .full-column'),
			$footer = $section.parent().siblings('.footer-row-wrapper'),
			footerIndex = '',
			$anchors = [],
			$names = [];
		$footer.addClass('fp-auto-height');
		$footer.find('.vc-row').addClass('footer-row');
		$section.parent().siblings('.footer-row-wrapper').appendTo($column);
		$section.parent().css('padding-top','0');

		$section.find('.vc_row:not(.footer-row)').each(function(i){
			if($(this).parent().hasClass('wpb_wrapper')) {
				return;
			};
			var $id = '';
			$id = ($(this).is('[id]')) ? $(this).attr('id') : '';

			if($id != '') {
				$anchors.push($id);
				$(this).removeAttr('id');
			} else {
				$anchors.push('section-'+(i+1));
			}
			if($(this).attr('data-tooltip')) {
				if($(this).data('tooltip').length > 0) {
					$names.push($(this).data('tooltip'));
				} else {
					$names.push(" ");
				}
			}
		});

		$column.fullpage({
			navigation: true,
			easingcss3: 'cubic-bezier(.29,.23,.13,1)',
			scrollingSpeed: 1000,
			sectionSelector: '>div',
			anchors: $anchors,
			navigationTooltips: $names,
			css3: true,
			scrollOverflow: true,
			fixedElements: '#mobile-navbar-home, .mobile-nav-overlay, #mobile-burger',
			lazyLoading: false,
			onLeave: function(index, nextIndex, direction) {

				var $indexRow = $section.find('.fullpage-wrapper > .fp-section:nth-child('+index+')');
				var $prevRow = $section.find('.fullpage-wrapper > .fp-section:nth-child('+(index-1)+')');
				var $nextIndexRow = $section.find('.fullpage-wrapper > .fp-section:nth-child('+nextIndex+')');

				if($(this).find('.veso-video-bg-row').length > 0) {
					$(this).find('.veso-video-bg-row')[0].jarallax.video.pause();
				}

				if($nextIndexRow.find('.veso-video-bg-row').length > 0) {
					$nextIndexRow.find('.veso-video-bg-row')[0].jarallax.video.play();
				}


				if(!$nextIndexRow.hasClass('footer-row-wrapper')) {
				
				$indexRow.find('.vc_column-inner .wpb_text_column > .wpb_wrapper > *, .wpb_wrapper .animate-text > *').velocity('stop');
				if(direction == 'down') {

					$indexRow.find('.fp-tableCell').css({'transition': 'transform 1s cubic-bezier(.29,.23,.13,1)', 'transform': 'translateY(50%)'});
					
					$nextIndexRow.find('.row-wrapper').css({'transition': 'transform .1s cubic-bezier(.29,.23,.13,1)', 'transform': 'translateY(-30%)'});

					setTimeout(function(){
						$nextIndexRow.find('.row-wrapper').css({'transition': 'transform 1s cubic-bezier(.29,.23,.13,1)', 'transform': 'translateY(0%)'});
						
					}, 50);

					setTimeout(function(){ 
						if(nextIndex != footerIndex) {
							$indexRow.find('.vc_column-inner .wpb_text_column > .wpb_wrapper > *, .wpb_wrapper .animate-text > *').css({'opacity' : '0'});
							$nextIndexRow.find('.vc_column-inner .wpb_text_column > .wpb_wrapper > *, .wpb_wrapper .animate-text > *').velocity('transition.slideDownBigIn', { stagger: 200, drag: true}); 
						}
						$indexRow.find('.fp-tableCell').css({'transform': 'translateY(0)', 'transition': 'transform 0s cubic-bezier(.29,.23,.13,1)' });
						window.bLazy.load($nextIndexRow.find('.b-lazy'));
					}, 1000);
				} else {
					if(nextIndex !== footerIndex-1) {
						$indexRow.find('.fp-tableCell').css({'transform': 'translateY(-50%)','transition': 'transform 1s cubic-bezier(.29,.23,.13,1)' })

						$nextIndexRow.find('.row-wrapper').css({'transition': 'transform .1s cubic-bezier(.29,.23,.13,1)', 'transform': 'translateY(30%)'});
						setTimeout(function(){
							$nextIndexRow.find('.row-wrapper').css({'transition': 'transform 1s cubic-bezier(.29,.23,.13,1)', 'transform': 'translateY(0%)'});
						}, 50);


						setTimeout(function(){
							$indexRow.find('.fp-tableCell').css({'transform': 'translateY(0)','transition': 'transform 0s cubic-bezier(.29,.23,.13,1)', });
							$indexRow.find('.vc_column-inner .wpb_text_column > .wpb_wrapper > *, .wpb_wrapper .animate-text > *').css({'opacity' : '0'});
							$nextIndexRow.find('.vc_column-inner .wpb_text_column > .wpb_wrapper > *, .wpb_wrapper .animate-text > *').velocity('transition.slideDownBigIn', { stagger: 200, drag: true});
					
							window.bLazy.load($nextIndexRow.find('.b-lazy'));
						}, 1000);
					}
		
				}
				}
			},
			afterRender: function(){
				$section.find('.fp-section:not(.footer-row-wrapper) .vc_column-inner .wpb_text_column > .wpb_wrapper > *, .fp-section:not(.footer-row-wrapper) .wpb_wrapper .animate-text > *').css("opacity", 0);
				footerIndex = $section.find('.fullpage-wrapper > .footer-row-wrapper').index()+1;
				if(footerIndex) {
					$('#fp-nav').find('li:last-of-type()').remove();
				}

				if($(this).find('.veso-video-bg-row').length > 0) {
					$section.find('.veso-video-bg-row')[0].jarallax.video.play();	
				}	

				setTimeout(function(){
					$section.find('.fullpage-wrapper > .fp-section.active:nth-child(1) .vc_column-inner .wpb_text_column > .wpb_wrapper > *, .fullpage-wrapper > .fp-section.active:nth-child(1) .wpb_wrapper .animate-text > *').velocity('transition.slideDownBigIn', { stagger: 200, drag: true});
				}, 500);
		
				$section.find('.fp-tableCell').each(function(){
					$(this).find('> .vc_row-full-width, > .vc_row, .fp-scroller > .vc_row, .fp-scroller > .vc_row-full-width').wrapAll('<div class="row row-wrapper" style="height: 100%; width: 100%"><div class="columns small-12"></div></div>');
				});

				$(window).trigger('resize');
			},
 
		});
	});
}

// Contact Form
function initContactForm() {
	$('.wpcf7').addClass('form-style');
	var value  = $('.wpcf7 .form-submit .wpcf7-submit').val();
	$('.wpcf7 .form-submit .wpcf7-submit').remove();
	$('.wpcf7 .form-submit').append('<button name="submit" type="submit" class="btn veso-header btn-solid btn-dark btn-md btn-contact wpcf7-form-control wpcf7-submit" tabindex="5" id="submit"><span class="btn-text">'+value+'</span></button>');
	$('.wpcf7 .veso-input').each(function(){
		var $that = $(this);
		if($that.find('.wpcf7-form-control').hasClass('wpcf7-validates-as-required')) {
			$that.addClass('input-required');
		}
		$that.on('keyup', function() {
			if($that.find('span').hasClass('wpcf7-not-valid-tip')) {
				if ($that.find('input, textarea, select').val().length > 0) {
					$that.find('input, textarea, select').removeClass('wpcf7-not-valid');
				} else {
					$that.find('input, textarea, select').addClass('wpcf7-not-valid');
				}
			}
		});
	});
}

// Footer position
function setFooter() {
	var page_height = $(window).outerHeight(),
		footer_height = $('.footer').outerHeight(),
		content_height = $('.page-wrapper').outerHeight(),
		nav_height = $('.veso-nav').outerHeight();

	if(page_height >= nav_height + content_height + footer_height) {
		$('.footer').css('position', 'absolute');	
	} else {
		$('.footer').css('position', 'relative');
	}
}

// VC Interactive Box
function setInteractiveBox() {
	$('.interactive-box').each(function(){
		var $that = $(this);
		var heightSmall = $that.data('smHeight');
		var heightLarge = $that.data('lgHeight');
		if($that.find('.box-content a').length > 0) {
			$that.find('.box-link').css('z-index', '-1');
		} 
		if($(window).width() < 768) {
			$that.height(heightSmall);
		} else {
			$that.height(heightLarge);
		}
		$that.find('.lightbox').magnificPopup({
			type:'image', 
			gallery:{
				enabled:true
			}
		});
	
	});
	$('.box-slider').owlCarousel({
		items: 1,
		loop: true,
		autoplay: false,
		autoplaySpeed: 3000
	});
}

// Comment list
function showCommentList() {
	$('#comments .comment-list').each(function(){
		var $that = $(this);
		if($that.find('> li.comment').length == 1) {
			$that.find('> li.comment').addClass('first-li last-li');
		}
		$that.find('> li.comment').siblings('.comment').last().addClass('last-li');
		$that.find('> li.comment').siblings('.comment').first().addClass('first-li');
		$that.prepend('<div class="border-list"></div>');
	});
	$('.first-li').each(function(){
		var $li = $(this),
			li_offset = $li.find('.comment-author-avatar').offset().top,
			list_offset = $li.parent().offset().top;
		$li.parent().find('> .border-list').css('top', li_offset - list_offset);
	});
	$('.last-li').each(function(){
		var $li = $(this),
			li_offset = $li.find('.comment-author-avatar').offset().top + 60,
			list_offset = $li.offset().top + $li.height();
		if($li.has('.comment-list').length) {
			var next_li_offset = $li.find('> .comment-list > .last-li').find('.comment-author-avatar').offset().top + 30;
			$li.parent().find('> .border-list').css('bottom', list_offset - next_li_offset);
		} else {
			$li.parent().find('> .border-list').css('bottom', list_offset - li_offset);
		}
	});
}

// Burger navigation
function showBurgerNav() {
	$('.veso-nav-burger').on('click', function(){
		$('.veso-nav-overlay').toggleClass('veso-overlay-open');
		if(!$('.veso-nav-overlay').hasClass('veso-overlay-open')) {
			setTimeout(function(){
				$('body').removeClass('no-scroll');
				$('body, .fixed-nav').css('width','auto');
			}, 600);
		} else {
			$('body, .fixed-nav').css('width',$('body').width());
			$('body').addClass('no-scroll');
		}
		$(window).trigger('resize.vcRowBehaviour');
		$('.veso-body-overlay').toggleClass('open');
	});
	$('.veso-body-overlay').on('click', function(){
		$('.veso-nav-overlay').removeClass('veso-overlay-open');
		$(this).removeClass('open');
			setTimeout(function(){
				$('body').removeClass('no-scroll');
				$('body').css('width','auto');
			}, 600);
	});

	$('.cart-offcanvas-close, .cart-overlay').on('click', function(){
		$('.cart-offcanvas').removeClass('show-cart-offcanvas');
	});
	$('.open-cart').on('click', function(e){
		e.preventDefault();
		$('.cart-offcanvas').addClass('show-cart-offcanvas');
	});
}
function openBurgerSubNavOverlay() {
	var easing = [0.645, 0.045, 0.355, 1];

	$('.veso-nav-overlay .sub-menu').prepend('<li class="overlay-back"><a href="#">'+vesoBackWord+'</a></li>');
	// $('.veso-nav-overlay .veso-menu-content .main-nav > .menu > li > a').wrap('<div class="item-wrapper"></div>');
	$('.veso-nav-overlay .menu-item-object-veso_mega_menu').addClass('megamenu').removeClass('menu-item-object-veso_mega_menu').addClass('menu-item-has-children');
	$('.veso-nav-overlay .menu-item-has-children > a').click(function(e){
		e.preventDefault();
		var $that = $(this),
			item_width = $that.parent().width(),
			windowHeight = $(window).height();
		// $that.parent().parent().find('.sub-menu > li > a').css('display','inline-block');
		var submenuHeight = $that.parent().find('.sub-menu').first().height() + 180 + $('.veso-nav-footer').height();
		if(windowHeight < submenuHeight) {
			$('.veso-nav-footer').velocity({opacity: 0});
		}		
		$('.veso-nav-overlay').addClass('first-level');
		$that.parent().parent().find('> li > a').velocity({ translateX: -item_width, opacity: 0}, {display: 'none', easing: easing});
		$('.veso-nav-overlay .submenu-active').removeClass('submenu-active');
		$that.parent().find('.sub-menu').first().addClass('submenu-active');
		$that.parent().find('.sub-menu').first().find('> li > a').velocity({ translateX: [0, item_width], opacity: 1}, {
			display: 'inline-block',
			easing: easing,
			complete: function(){
				submenuHeight = $that.parent().find('.sub-menu').first().height() + 180 + $('.veso-nav-footer').height();
			// $that.parent().find('.sub-menu').first().css('position', 'relative');
			
			// $that.parent().parent().find('.sub-menu').first().css('position', 'relative');
			$that.parent().find('.sub-menu').first().css('position', 'relative');
			if(windowHeight < submenuHeight) {
				$('.veso-nav-footer').velocity({opacity: 1});
			}
		}});
		var currentText = '';
		if($that.parents('.menu').find('> li > a > .overlay-back-header').length > 0) {
			$that.parents('.menu').find('> li > a > .overlay-back-header').each(function(){
				currentText += $(this).prop('outerHTML');
			});
		}
	});
	$('.veso-nav-overlay').on('click', '.overlay-back', function(e){
		e.preventDefault();
		$('.veso-nav-overlay').addClass('first-level');
		var $that = $(this),
			item_width = $that.width(),
			windowHeight = $(window).height(),
			submenuHeight = $that.parent().height() + 180 + $('.veso-nav-footer').height();

		if(windowHeight < submenuHeight) {
			$('.veso-nav-footer').velocity({opacity: 0}, {duration: 200, complete: function(){

		$that.parent().css('position', 'absolute');

		$that.find('a').velocity({ translateX: item_width, opacity: 0}, {display: 'none', easing: easing});
		$that.siblings().find('a').velocity({ translateX: item_width, opacity: 0}, {display: 'none', easing: easing});


		$('.veso-nav-overlay .submenu-active').removeClass('submenu-active');
		$that.parent().parent().parent().addClass('submenu-active');

		$that.parent().parent().parent().find('> li > a').velocity({translateX : 0, opacity: 1}, {display: 'inline-block', easing: easing, complete: function(){ $('.veso-nav-footer').velocity({opacity: 1})}});
			}});
		} else {
			$that.parent().css('position', 'absolute');

			$that.find('a').velocity({ translateX: item_width, opacity: 0}, {display: 'none', easing: easing});
			$that.siblings().find('a').velocity({ translateX: item_width, opacity: 0}, {display: 'none', easing: easing});


			$('.veso-nav-overlay .submenu-active').removeClass('submenu-active');
			$that.parent().parent().parent().addClass('submenu-active');

		$that.parent().parent().parent().find('> li > a').velocity({translateX : 0, opacity: 1}, {display: 'inline-block', easing: easing, complete: function(){ $('.veso-nav-footer').velocity({opacity: 1})}});
		}
	});
}

// Main navigation
function openSubnav() {
	var $el = $(this);
	if($el.data('nav-showed') == '1') {
		return false;
	}

	var $dropdown = $el.find('.sub-menu').first();

	var display = 'block';
	if($el.hasClass('menu-item-object-veso_mega_menu')) {
		display = 'flex';
	}
	
	$dropdown.velocity('stop').velocity({opacity: 1}, {duration: 200, display: display, complete: function(){
		$el.data('nav-showed', '1');
	}});

	if($el.hasClass('menu-item-object-veso_mega_menu')) {
		$dropdown.width($('.veso-nav .nav').width() / (12/parseInt($dropdown.data('width'))))
		$dropdown.css('left', $('.veso-nav .nav').offset().left - $dropdown.parent().offset().left + 15 );

		var parent_width = $el.width();
		var dropdown_width = $dropdown.width();
		var left_position = -(dropdown_width / 2) + (parent_width / 2);
		var dropdownPosition = $el.position().left + (parent_width/2) + left_position;
		var dropdownPositionRight = dropdownPosition + dropdown_width;
		var correctedPosition = left_position - (dropdownPositionRight - $('.veso-nav .nav').width() - $('.veso-nav .nav').position().left - (parent_width/2) - 15);
		$dropdown.css('left', left_position);
		if(dropdownPositionRight > $('.veso-nav .nav').width() + $('.veso-nav .nav').position().left ) {
			$dropdown.css('left', correctedPosition);
		} else if(dropdownPosition < $('.veso-nav .nav').position().left) {
			$dropdown.css('left', $('.veso-nav .nav').position().left - $el.position().left + 15);
		}
		
	} else {
		var moveLeft = '',
			position = $dropdown.parent().offset(),
			dropdownPosition = position.left;
		dropdownPosition = dropdownPosition - ($dropdown.width() / 2);

		$dropdown.css('margin-left', 0);

		if((position.left + $dropdown.width()) > $(window).width()) {
			moveLeft = (position.left + $dropdown.width()) - $(window).width();
		} else {
			moveLeft = '';
		}

		if($dropdown.length > 0) {
			if($dropdown.parent().hasClass('first-level')) {
				$dropdown.css('margin-left', ($dropdown.width() / -2) - moveLeft);
			} else {
				if($dropdown.parent().outerWidth()*2 + $dropdown.parent().offset().left > $(window).width()) {
					$dropdown.addClass('dropdown-left');
				} else {
					$dropdown.removeClass('dropdown-left');
				}
			}
		}
	}
}

function hideSubnav() {
	var $el = $(this);
	var $dropdown = $el.find('.sub-menu').first();
	$dropdown.velocity('stop').velocity({opacity: 0}, {duration: 300, display: 'none', complete: function(){
		$el.data('nav-showed', '0');
	}});
}

// Social Profiles in navigation
function vesoColorNavSocials() {
	if($('.nav-social-profiles').length == 0) 
		return false;

	var $styles = $('#veso-main-stylesheet-inline-css'),
		styleString = '',
		i = 0;
	$('.nav-social-profiles, .veso-social-profiles').each(function(){
		$(this).find('a').each(function(el){
			if($(this).data('color')) {
				var color = $(this).data('color');
				$(this).addClass('veso-social-st'+i);
				styleString += '.veso-social-st'+i+':hover i{color:'+color+'!important;}';
			}
			if($(this).data('bg')) {
				var bg = $(this).data('bg');
				$(this).addClass('veso-social-st'+i);
				styleString += '.veso-social-st'+i+':before,.veso-social-st'+i+':after{border-color:'+bg+'!important;}';
			}
			i++;
		});
	});
	$styles.text($styles.text() + styleString);
}

// Fixed Navigation
function initFixedNavigation() {
	var navHeight = $('.fixed-nav').data('height');
	$('#fixed-nav .nav').height(navHeight);
	var navbarWaypoint = new Waypoint({
		element: document.getElementById('main-navbar-home'),
		handler: function(direction) {
			if(direction == 'down') {
				$('#fixed-nav').addClass('show-fixed-nav');
			} else {
				$('#fixed-nav').removeClass('show-fixed-nav');
			}
		},
		offset: -navHeight+'px',
	});
}

// Portfolio
function initPortfolioMasonry() {
	$('.veso-portfolio-masonry').each(function(){
		var $portfolio = $(this);
		$portfolio.isotope({
			itemSelector: 'article',
			percentPosition: true,
			transitionDuration: '0s',
			masonry: {
				columnWidth: '.grid-sizer',
			},
			hiddenStyle: {

			},
			visibleStyle: {

			},
		});

		$(window).on('masonryLayout', function(){
			$portfolio.isotope('layout');
		});

		var canClick = true;
		var page = 2;
		var ppp = $portfolio.data('ppp');
		var hover = $portfolio.data('hover');
		var style = $portfolio.data('style');
		var masonry = $portfolio.data('ismasonry');
		var imgbg = $portfolio.data('imgbg');
		var categories = $portfolio.data('cat');
		var layout = $portfolio.data('layout');
		var customLayout = $portfolio.data('customLayout');
		var openPortfolio = $portfolio.data('open');
		$(this).parent().find('.veso-load-more').on('click', function(e){
			var button = $(this);
			var index = button.data('index');
			if(canClick == true) {
				canClick = false;			
				var $loadmore = $(this);
				e.preventDefault();
				$.ajax({
					url: rest_object.api_url + 'portfolio/',
					type: 'POST',
					dataType: 'json',
					data: { page : page, posts_per_page: ppp, hover : hover, style : style, masonry : masonry, imgbg : imgbg, categories: categories, layout: layout, custom_layout: customLayout, open_portfolio: openPortfolio, index: index },
					beforeSend: function ( xhr ) {
						xhr.setRequestHeader( 'X-WP-Nonce', rest_object.api_nonce );
					},
				}).done(function(response) {
					var $content = $( response.output );
					$portfolio.append($content).isotope('appended', $content);
					setTimeout(function(){

						window.bLazy.revalidate();
						showPortfolioHover();
					}, 100)
					setHeaderSizePortfolio();
					if(response.next === false) {
						$loadmore.remove();
					} else {
						page = response.next;
					}
					button.data('index', response.index);
					canClick = true;
					$(window).trigger('refreshLigthboxes');
				}).fail(function() {
					canClick = true;
				});
			}

		})


	});
}

function initVesoPortfolioCarousel() {
	$('.veso-portfolio-carousel').each(function(){
		var $that = $(this),
			$autoplay = $that.data('autoplay'),
			$arrows = $that.data('arrows'),
			$pagination = $that.data('pagination'),
			$nextButton = '',
			$prevButton = '',
			$delay = '',
			$speed = 500;

		if($autoplay == '1') {
			$autoplay = $that.data('speed');
			$speed = 1000;
		} else { 
			$autoplay = '';
		}
		if($pagination == '1') {
			$pagination = false;
		} else { 
			$pagination = true;
		}
		if($arrows == '1') {
			$prevButton = $that.parent().find('.veso-gallery-arrows .arrow-prev'),
			$nextButton = $that.parent().find('.veso-gallery-arrows .arrow-next');
		} else {
			$nextButton = '';
			$prevButton = '';
		}
		var swiper = new Swiper($(this)[0], {
			pagination: '.veso-gallery-pagination',
			paginationHide: $pagination,
			slidesPerView: 'auto',
			paginationClickable: true,
			keyboardControl: true,
			mousewheelControl: true,
			spaceBetween: 30,
			speed: $speed,
			autoplay: $autoplay,
			autoplayStopOnLast: true,
			nextButton: $nextButton,
			prevButton: $prevButton,

			onSlideChangeEnd: function(swiper){
				window.bLazy.revalidate();
			},
			onInit: function(swiper){
				// window.bLazy.revalidate();
			},
		});
		var dataHeight = $that.data('height');
		if(dataHeight == 'full') {
			if($that.find('.veso-portfolio-item').hasClass('text-on-hover')) {
				$that.find('.swiper-slide,img').height( $(window).height() - $('.veso-nav').height() );
				$(window).on('resize', function(){
					$that.find('.swiper-slide,img').height( $(window).height() - $('.veso-nav').height() );
				})
			} else {
				$that.find('img').height( $(window).height() - $('.veso-nav').height() - $('.portfolio-text').outerHeight());
				$(window).on('resize', function(){
					$that.find('img').height( $(window).height() - $('.veso-nav').height() - $('.portfolio-text').outerHeight());
				})
				$that.find('.swiper-pagination').css('bottom', $('.portfolio-text').outerHeight() + 30);
			}
		} else {
			if($that.find('.veso-portfolio-item').hasClass('text-on-hover')) {
				$that.find('.swiper-slide,img').height(dataHeight);
				$that.height(dataHeight);
			} else {
				$that.height(dataHeight);
				$that.find('img').height(dataHeight - $('.portfolio-text').outerHeight());
				$that.find('.swiper-pagination').css('bottom', $('.portfolio-text').outerHeight() + 30);
			}
		}
		if($that.find('.veso-portfolio-item').hasClass('text-below')) {
			var textHeight = $that.find('.portfolio-text').height() + 50;
			var textHalfHeight = (textHeight + 15) /2;
			$that.find('.veso-gallery-pagination').css('bottom',textHeight+'px');
			$that.find('.veso-gallery-arrows .arrow').css('margin-top',-textHalfHeight+'px');
		}
		$that.imagesLoaded().progress( function() {
			swiper.update();
		});
	})
}

function showPortfolioHover() {
	var $transform = prefix['js']+'Transform';
	$('.veso-portfolio-item.text-on-hover.hover3:not(.hover-ready)').each(function(){
		var $that = $(this);
		var $text = $that.find('.portfolio-text');
		$that.addClass('hover-ready');
		$that.find('.portfolio-text').css('position', 'fixed');
		if($that.parent().hasClass('swiper-slide')) {
			var swiperContainer = $that.parents('.swiper-container');
			$that.on('mousemove', function(e){
				var offset = $that.position();
				var left = e.pageX + 30 - swiperContainer.data('swiper').translate;
				var top =  e.pageY + 30 - $(window).scrollTop() - swiperContainer.offset().top;
				$text.css('left', left);
				$text.css('top', top);
			});
		} else {
			$that.on('mousemove', function(e){
				var offset = $that.position();
				var left = e.pageX + 30;
				var top =  e.pageY + 30 - $(window).scrollTop();
				$text.css('left', left);
				$text.css('top', top);
			});
		}
	});
	$('.veso-portfolio-item.text-on-hover.hover5:not(.hover-ready)').each(function(){
		var $that = $(this);
		var $image = $that.find('img, .portfolio-img')
		$that.addClass('hover-ready');
		$that.on('mouseenter', function(){
			var textHeight = $that.find('.portfolio-text').outerHeight();
			$image.velocity({translateY : -textHeight}, 0);
		});
		$that.on('mouseleave', function(){
			$image.velocity({translateY : 0}, 0);
		});
	});
}

function setHeaderSizePortfolio() {
	$('.veso-portfolio').each(function(){
		var $that = $(this),
			headerSize = $that.data('headerSize'),
			mod = '';
		$that.find('.portfolio-text h3').addClass('h'+headerSize);
		var categorySize = parseInt($that.find('.portfolio-text h3').css('font-size'));
		if(headerSize == "1") {
			mod = .33;
		} else if(headerSize == '2') {
			mod = .4
		} else if(headerSize == '3') {
			mod = .45
		} else {
			mod = .6;
		}
		$that.find('.portfolio-text ul a').css('font-size', categorySize*mod);
	});
}

function openPortfolioLightbox(){
	$('.veso-portfolio-item.open-lightbox').each(function(){
		var $that = $(this);
		$that.find('.lightbox-gallery a').magnificPopup({
			type:'image', 
			gallery:{
				enabled:true
			}
		});
	});

	$('.veso-portfolio').each(function(){
		var $el = $(this).find('.images-lightbox');
		$el.magnificPopup({
			type:'image', 
			gallery:{
				enabled:true
			},
			disableOn: 700,
			mainClass: 'mfp-fade',
			removalDelay: 160,
			preloader: false,
		});
	});

} 

// Single Portfolio
function initSingleGallery() {
	$('.single-gallery-slider').each(function(){
		$(this).find('.swiper-slide').magnificPopup({
			delegate: 'a',
			type:'image', 
			gallery:{
				enabled:true
			}
		});
		var sliderSingleGallery = new Swiper(this, {
			speed: 750,
			slidesPerView: 1,
			autoplay: false,
			centeredSlides: true,
			loop: true,
			autoHeight: true,
			effect: 'fade',
			fade: {crossFade: true},
			direction: 'horizontal',
			mousewheelControl: false,
			grabCursor: false,
			slideClass: 'swiper-slide',
			keyboardControl: true,
			nextButton: '.single-gallery-arrows .arrow-next',
			prevButton: '.single-gallery-arrows .arrow-prev',
		});
	});
	$('.single-gallery-masonry').each(function(){
		var $gallery = $(this);
		$gallery.isotope({
			itemSelector: 'article',
			percentPosition: true,
			transitionDuration: '0.4s',
			masonry: {
				columnWidth: '.grid-sizer',
			},
			hiddenStyle: {
				opacity: 0,
				transform: 'translate3d(0, 30px, 0)',
			},
			visibleStyle: {
				opacity: 1,
				transform: 'translate3d(0, 0, 0)',
			},
		});
		$gallery.magnificPopup({
			delegate: 'a',
			type:'image', 
			gallery:{
				enabled:true
			}
		});
	});
	$('.veso-single-gallery:not(.single-gallery-slider)').each(function(){
		var $that = $(this);
		$that.magnificPopup({
			delegate: 'a',
			type:'image', 
			gallery:{
				enabled:true
			}
		});
	});


	$('.veso-single-gallery-grid').isotope({
			itemSelector: 'div',
			percentPosition: true,
			transitionDuration: '0s',
			masonry: {
				columnWidth: '.grid-sizer',
			},
			hiddenStyle: {

			},
			visibleStyle: {

			},
		});
}

function initFixedContent() {
	$('.veso-single-portfolio').each(function() {
		var $gallery = $(this).find('.veso-single-gallery'),
			$content = $(this).find('.single-portfolio-content'),
			galleryHeight = $gallery.outerHeight() - 30,
			contentHeight = $content.outerHeight() + 30;
		if($content.length > 0 && contentHeight < galleryHeight) {
			var navHeight = $('#fixed-nav').height();
			$content.stick_in_parent({offset_top: navHeight});
		}
	}); 
} 

// VC Gallery
function initVesoPostGallery() {
	$('.veso-post-gallery').each(function(){
		var $postGallery = $(this);
		var $arrowPrev = $postGallery.parent().find('.veso-post-gallery-arrows .arrow-prev');
		var $arrowNext = $postGallery.find('.veso-post-gallery-arrows .arrow-next');
		var sliderVesoPostGallery = new Swiper($postGallery, {
			speed: 1000,
			slidesPerView: 1,
			autoplay: false,
			loop: true,
			effect: 'slide',
			direction: 'horizontal',
			mousewheelControl: false,
			grabCursor: false,
			slideClass: 'swiper-slide',
			keyboardControl: true,
			nextButton: $arrowNext,
			prevButton: $arrowPrev,
		});
	});
}

// Blog
function initBlogMasonry() {
	$('.veso-blog-grid').each(function(){
		var $blog = $(this),
			$blogInner = $(this).find('.blog-inner'),
			layoutMode = 'masonry';

		if($blog.hasClass('veso-grid')) {
			layoutMode = 'fitRows';
		}

		$blogInner.isotope({
			itemSelector: 'article',
			percentPosition: true,
			transitionDuration: '.4s',
			masonry: {
				columnWidth: '.grid-sizer',
			},
			layoutMode: layoutMode,
			hiddenStyle: {
				opacity: 0,
			},
			visibleStyle: {
				opacity: 1,
			},
		});

		$(window).on('masonryLayout', function(){
			$blogInner.isotope('layout');
		});

		var canClick = true,
			page = 2,
			ppp = $blog.data('ppp'),
			imgbg = $blog.data('imgbg'),
			categories = $blog.data('cat'),
			showCat = $blog.data('showCat'),
			alignment = $blog.data('alignment'),
			gridClass = $blog.data('gridClass'),
			showImg = $blog.data('showImg'),
			style = $blog.data('style');

		$(this).find('.veso-load-more').on('click', function(e){
			if(canClick == true) {
				canClick = false;			
				var $loadmore = $(this);
				e.preventDefault();
				$.ajax({
					url: rest_object.api_url + 'blog/',
					type: 'POST',
					dataType: 'json',
					data: { page : page, posts_per_page: ppp, imgbg : imgbg, categories: categories, grid_class: gridClass, alignment: alignment, show_img: showImg, show_cat: showCat, style: style },
					beforeSend: function ( xhr ) {
						xhr.setRequestHeader( 'X-WP-Nonce', rest_object.api_nonce );
					},
				}).done(function(response) {
					var $content = $( response.output );
					$blogInner.append($content).isotope('appended', $content);
					setTimeout(function(){

						window.bLazy.revalidate();
						$('.post-meta:not(.loaded-post)').addClass('loaded-post');
					}, 100)
					setHeaderSizePortfolio();
					if(response.next === false) {
						$loadmore.remove();
					} else {
						page = response.next;
					}
					canClick = true;
				}).fail(function() {
					canClick = true;
				});
			}
		})
	});
}

function initVesoBlogCarousel() {
	$('.veso-blog-carousel').each(function(){
		var $carousel = $(this),
		$autoplay = $carousel.data('autoplay'),
		$slidesCount = $carousel.data('perview'),
		$pagination = $carousel.find('.veso-carousel-pagination'),
		$speed = 500;
		if($autoplay == '1') {
			$autoplay = 3000;
			$speed = 2000;
		} else { 
			$autoplay = '';
		}
	var sliderBlogCarousel = new Swiper($carousel, {
		speed: $speed,
		slidesPerView: $slidesCount,
		autoplay: $autoplay,
		loop: true,
		effect: 'slide',
		direction: 'horizontal',
		mousewheelControl: false,
		grabCursor: false,
		spaceBetween: 30,
		slideClass: 'swiper-slide',
		keyboardControl: true,
		pagination: $pagination,
		paginationClickable: true,
		autoplayDisableOnInteraction: false,
		setWrapperSize: true,
		breakpoints: {
			480: {
				slidesPerView: 1,
				spaceBetween: 0,
			},
			640: {
				slidesPerView: 2,
			},
			1024: {
				slidesPerView: 3,
			}
		},
		onSlideChangeEnd: function(swiper){
			window.bLazy.revalidate();
		},
	});
	});
}

function vesoInitBlogStaticLoadMore() {
	$('.veso-blog-chessboard, .veso-blog-list').each(function(){
		var $blog = $(this),
			$blogInner = $(this).find('.blog-inner'),
			canClick = true,
			page = 2,
			ppp = $blog.data('ppp'),
			imgbg = $blog.data('imgbg'),
			categories = $blog.data('cat'),
			showCat = $blog.data('showCat'),
			alignment = $blog.data('alignment'),
			gridClass = $blog.data('gridClass'),
			showImg = $blog.data('showImg'),
			style = $blog.data('style');

		$(this).find('.veso-load-more').on('click', function(e){
			if(canClick == true) {
				canClick = false;			
				var $loadmore = $(this);
				e.preventDefault();
				$.ajax({
					url: rest_object.api_url + 'blog/',
					type: 'POST',
					dataType: 'json',
					data: { page : page, posts_per_page: ppp, imgbg : imgbg, categories: categories, grid_class: gridClass, alignment: alignment, show_img: showImg, show_cat: showCat, style: style },
					beforeSend: function ( xhr ) {
						xhr.setRequestHeader( 'X-WP-Nonce', rest_object.api_nonce );
					},
				}).done(function(response) {
					var $content = $( response.output );
					$blogInner.append($content);
					setTimeout(function(){
						window.bLazy.revalidate();
						$('.post-meta:not(.loaded-post)').addClass('loaded-post');
					}, 100)
					setHeaderSizePortfolio();
					if(response.next === false) {
						$loadmore.remove();
					} else {
						page = response.next;
					}
					canClick = true;
				}).fail(function() {
					canClick = true;
				});
			}

		})


	});
}

var prefix = (function () {
	var styles = window.getComputedStyle(document.documentElement, ''),
	pre = (Array.prototype.slice
		.call(styles)
		.join('')
		.match(/-(moz|webkit|ms)-/) || (styles.OLink === '' && ['', 'o'])
		)[1],
	dom = ('WebKit|Moz|MS|O').match(new RegExp('(' + pre + ')', 'i'))[1];
	return {
		dom: dom,
		lowercase: pre,
		css: '-' + pre + '-',
		js: pre[0].toUpperCase() + pre.substr(1)
	};
})();

// VC Full page slider
function initVesoSlider() {
	$('.veso-slider').each(function(){
		var $slider = $(this);
		var sliderVesoSlider = new Swiper($slider, {
			speed: 1000,
			slidesPerView: 1,
			autoplay: 4000,
			loop: false,
			effect: 'fade',
			direction: 'horizontal',
			mousewheelControl: false,
			grabCursor: false,
			slideClass: 'swiper-slide',
			keyboardControl: true,
		});
	});

	$('.veso-parallax-slider').each(function () {
		var $content_item = $(this).find('.veso-slider');

		$(this).jarallax({
			type: 'custom',
			imgSrc: 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7',
			imgWidth: 1,
			imgHeight: 1,
			speed: 0,
			onScroll: function (calculations) {
				var scrollContent = calculations.afterTop - calculations.beforeBottom;
				$content_item.css({
					transform: 'translate3d(0, ' + scrollContent + 'px, 0)'
				});
			}
		});
	});	
}

function initVesoSlideNav() {
	$('.veso-slider-container').each(function(){
		var $that = $(this),
			windowWidth = $(window).width(),
			count_slides = '',
			mobile_height = $that.find('.slide-nav').data('mobile-height'),
			$slideNav = $that.find('.slide-nav');
		var slideNavEl = $slideNav[0];
		if(windowWidth >=1200 && windowWidth < 1440) {
			count_slides = 3;
		} else {
			count_slides = $that.data('count-slides');
		}
		var list = $that.find('.veso-slide-content'),
			totalWidth = calculateWidth(list,count_slides),
			scrollDifference = totalWidth - windowWidth,
			slideHalf = (windowWidth / count_slides) / 2;

		if(windowWidth >= 1200) {
			$that.find('.veso-slide-content').height('inherit');
			if (list.length >= count_slides) {
				$that.on('mousemove', function(e) {
					var offsetLeft = -Math.floor(scrollDifference * (e.clientX / windowWidth));
					if(e.clientX <= slideHalf) {
						offsetLeft = offsetLeft - (e.clientX - slideHalf)
						if(offsetLeft > 0) {
							offsetLeft = 0;
						}
					} else if(e.clientX >= (windowWidth - slideHalf)) {
						offsetLeft = offsetLeft + (windowWidth - e.clientX - slideHalf)
						if(offsetLeft <= -scrollDifference) {
							offsetLeft = -scrollDifference;
						}
					}
					slideNavEl.style.transform = 'translate3d('+offsetLeft+'px, 0, 0)';
				});
			} else {
				var percentage = Math.round(100 / list.length);
				list.each(function(index, item) {
					$('.veso-slide-content').css({ 'width': percentage + '%' });
				}); 
			}
			$that.removeClass('slide-mobile');
		} else {
			$that.addClass('slide-mobile');
			$that.find('.veso-slide-content').css({ 'width': '100%' });
			$slideNav.offset({ left: 0 });
			$that.find('.veso-slide-content').height(mobile_height);
		}			

	});
}

var sliderTimer;
$(window).on('resize', function(){
	clearTimeout(sliderTimer);
	sliderTimer = setTimeout(function(){
		initVesoSlideNav();
	},100)
});

function calculateWidth(list,count_slides) {
	$('.slide-nav').find('.veso-slide-content').width((100/count_slides)+ '%');
	var totalWidth = $('.slide-nav').find('.veso-slide-content').width()*list.length;

	return totalWidth;
}

// VC TTA
function changeColorTabs() {
	$('.vc_tta-container').each(function(){
		var $tta = $(this),
			ttaTxt = $tta.find('.vc_tta').data('txt'),
			ttaTitle = $tta.find('.vc_tta').data('title'),
			ttaColor = $tta.find('.vc_tta').data('color'),
			ttaBorder = $tta.find('.vc_tta').data('border'),
			ttaActiveColor = $tta.find('.vc_tta').data('active'),
			ttaAccentColor = $tta.find('.vc_tta').data('accent');
		$tta.find('.vc_tta-tab:not(.vc_active)').css('background',ttaColor);
		$tta.find('.vc_tta-tab.vc_active').css('background',ttaActiveColor);
		$tta.find('.vc_tta-panels').css({'background':ttaActiveColor, 'color': ttaTitle});
		$tta.on('click', '.vc_tta-tab:not(.vc_active)', function() {
			var $tab = $(this);
			$tta.find('.vc_tta-tab').css('background',ttaColor);
			$tab.css('background',ttaActiveColor);
		});
		$tta.find('.vc_tta-tab a').css('color', ttaTitle);
		$tta.find('.vc_tta-tabs-container, .vc_tta-panels-container').css('border-color', ttaAccentColor);

		$tta.find('.vc_tta-accordion .vc_tta-panel-body p').css('color', ttaTxt);
		$tta.find('.vc_tta-accordion .vc_tta-panel-heading').css('border-color', ttaBorder);
		$tta.find('.vc_tta-accordion .vc_tta-panel').css('border-color', ttaColor);

	});
}

// VC Counter
function initVesoCounter() {
	$('.veso-counter').each(function() {
		var endNum = parseFloat($(this).find('.counter').data('counter'));
		var Num = ($(this).find('.counter').data('counter'))+' ';
		var speed = parseInt($(this).find('.counter').data('speed'));
		var ID = $(this).find('.counter').data('id');
		var sep = $(this).find('.counter').data('separator');
		var dec = $(this).find('.counter').data('decimal');
		var dec_count = Num.split(".");
		if(dec_count[1]){
			dec_count = dec_count[1].length-1;
		} else {
			dec_count = 0;
		}
		var grouping = true;
		if(dec == "none"){
			dec = "";
		}
		if(sep == "none"){
			grouping = false;
		} else {
			grouping = true;
		}
		var settings = {
			useEasing : true, 
			useGrouping : grouping, 
			separator : sep, 
			decimal : dec
		}
		var counter = new countUp(ID, 0, endNum, dec_count, speed, settings);
		setTimeout(function(){
			counter.start();
		},500);
	});
}

// VC Countdown
function vesoInitCountdown() {
	$('.veso_countdown-dateAndTime').each(function(){
		var t = new Date($(this).html());
		var tz = $(this).data('time-zone')*60;
		var tfrmt = $(this).data('countformat');
		var labels_new = $(this).data('labels');
		var new_labels = labels_new.split(",");
		var labels_new_2 = $(this).data('labels2');
		var new_labels_2 = labels_new_2.split(",");
		var font_count = $(this).data('font-count');
		var font_text = $(this).data('font-text');
		var color = $(this).data('color-text');
		var server_time = function(){          
		  return new Date($(this).data('time-now'));
		}
		var ticked = function (a){
			$(this).find('.veso_countdown-period').css('font-size',font_text);
			$(this).find('.veso_countdown-period').css('color',color);
			$(this).find('.veso_countdown-amount').css('font-size',font_count);
		}

	if($(this).hasClass('veso-usrtz')){
		$(this).veso_countdown({labels: new_labels, labels1: new_labels_2, until : t, format: tfrmt, padZeroes:true,onTick:ticked});
	}else{
		$(this).veso_countdown({labels: new_labels, labels1: new_labels_2, until : t, format: tfrmt, padZeroes:true,onTick:ticked , serverSync:server_time});
	}
	});

}

// VC Carousel
function initVesoCarousel() {
	$('.veso-carousel').each(function(){
		var $carousel = $(this),
		$autoplay = $carousel.data('autoplay'),
		$loop = $carousel.data('slide-loop'),
		$arrows = $carousel.data('arrows'),
		$slidesCount = $carousel.data('slides'),
		$pagination = $carousel.parent().find('.veso-carousel-pagination'),
		$arrowPrev = $carousel.find('.veso-carousel-arrows .arrow-prev'),
		$arrowNext = $carousel.find('.veso-carousel-arrows .arrow-next');
		var breakpoint = '';
		if($slidesCount >= 3) {
			breakpoint = {
				480: {
					slidesPerView: 1,
					spaceBetween: 0,
				},
				640: {
					slidesPerView: 2,
				},
				1024: {
					slidesPerView: 3,
				}
			}
		} else if($slidesCount == 2) {
			breakpoint = {
				480: {
					slidesPerView: 1,
					spaceBetween: 0,
				},
				640: {
					slidesPerView: 2,
				},
			}
		} 
	var sliderRedaCarousel = new Swiper($carousel, {
		speed: 1000,
		slidesPerView: $slidesCount,
		autoplay: $autoplay,
		loop: $loop,
		autoHeight: true,
		effect: 'slide',
		direction: 'horizontal',
		mousewheelControl: false,
		grabCursor: false,
		spaceBetween: 30,
		slideClass: 'swiper-slide',
		keyboardControl: true,
		pagination: $pagination,
		nextButton: $arrowNext,
		prevButton: $arrowPrev,
		paginationClickable: true,
		autoplayDisableOnInteraction: false,
		breakpoints: breakpoint,
		onInit: function(){
			$carousel.find('.img-wrapper').addClass('loaded');
		}
	});
	});
}

// VC Split slider
function initVesoSplitSlider() {
	$('.veso-split-slider').each(function(){
		var $slider = $(this),
		autoplay = $slider.data('autoplay'),
		$pagination = $slider.find('.veso-split-slider-pagination'),
		mousewheel = $slider.data('mouseWheel'),
		bgColorArray = [],
		colorArray = [];
		$slider.find('.veso-split-slide-item').each(function(){
			var $that = $(this),
				bgColor = $that.data('bgColor'),
				color = $that.data('color');
			bgColorArray.push(bgColor);
			colorArray.push(color);

		});
		var firstBg = $slider.find('.veso-split-slide-item:nth-child(1)').data('bgColor');
		$slider.css('background-color',firstBg);

		var firstColor = $slider.find('.veso-split-slide-item:nth-child(1)').data('color');
	
		var splitSlider = new Swiper($slider, {
			speed: 800,
			slidesPerView: 1,
			autoplay: autoplay,
			loop: true,
			autoHeight: true,
			effect: 'slide',
			direction: 'vertical',
			// mousewheelControl: false,
			grabCursor: false,
			// spaceBetween: $space,
			mousewheelControl: mousewheel,
			slideClass: 'swiper-slide',
			keyboardControl: true,
			pagination: $pagination,
			paginationClickable: true,
			autoplayDisableOnInteraction: false,
			nextButton: '.veso-split-slider-arrows .arrow-next',
			prevButton: '.veso-split-slider-arrows .arrow-prev',
			onSlideNextStart: function(swiper) {
				var index = swiper.realIndex; 
				$slider.css('background-color',bgColorArray[index]);
				$slider.find('.veso-split-slider-pagination .swiper-pagination-bullet').css('background-color',
					colorArray[index]);
				$slider.find('.veso-split-slider-arrows .arrow').css('color',colorArray[index]);
				$slider.find('.split-slider-btn').css('color',colorArray[index]);
				$slider.find('.veso-split-slide-desc').removeClass('active');
				$slider.find('.btn-container, .btn-container .split-slider-btn').removeClass('active');
			},
			onSlidePrevStart: function(swiper) {
				var index = swiper.realIndex;
				$slider.css('background-color',bgColorArray[index]);
				$slider.find('.veso-split-slider-pagination .swiper-pagination-bullet').css('background-color',colorArray[index]);
				$slider.find('.veso-split-slider-arrows .arrow').css('color',colorArray[index]);
				$slider.find('.split-slider-btn').css('color',colorArray[index]);
				$slider.find('.veso-split-slide-desc').removeClass('active');
				$slider.find('.btn-container, .btn-container .split-slider-btn').removeClass('active');
			},
			onSlideChangeEnd: function(swiper) {
				var index = swiper.realIndex;
				$slider.find('.swiper-slide-active .slide-img, .swiper-slide-duplicate-active .slide-img').addClass('active');
			},
			onSlideNextEnd: function(swiper) {
				var index = swiper.realIndex;
				$slider.find('.veso-split-slide-item:not(.swiper-slide-active, .swiper-slide-duplicate-active) .slide-img').removeClass('active');
				$slider.find('.veso-split-slide-desc:nth-child('+(index+1)+')').addClass('active');
				$slider.find('.btn-container, .btn-container .split-slider-btn:nth-child('+(index+1)+')').addClass('active');
			},
			onSlidePrevEnd: function(swiper) {
				var index = swiper.realIndex;
				$slider.find('.veso-split-slide-item:not(.swiper-slide-active, .swiper-slide-duplicate-active) .slide-img').removeClass('active');
				// $slider.find('.veso-split-slide-desc').removeClass('active');
				$slider.find('.veso-split-slide-desc:nth-child('+(index+1)+')').addClass('active');
				$slider.find('.btn-container, .btn-container .split-slider-btn:nth-child('+(index+1)+')').addClass('active');
			},
			onInit: function(swiper) {
				var index = swiper.realIndex;
				$slider.find('.swiper-slide-active .slide-img').addClass('active');
			}
		});
		$slider.find('.veso-split-slider-pagination').css('color',firstColor);
		$slider.find('.veso-split-slider-arrows .arrow').css('color',firstColor);
		$slider.find('.split-slider-btn').css('color',firstColor);
	});
}

// VC Progress bar
function initProgressBar() {
	$('.vc_progress_bar .vc_single_bar').each(function() { 
		var bar = $(this).find('.vc_label_units');
		var max = bar.parents('.vc_single_bar').find('.vc_bar').data('value');
		var maxPrecent = bar.parents('.vc_single_bar').find('.vc_bar').data('value') + '%';
		var time = max / 1
		bar.velocity({left: maxPrecent}, {duration: 1500, easing: "linear",  progress: function(elements, complete, r, s, t) {
			complete = Math.round(complete * 100);
			if(complete <= max) {
				$(this).html((complete) + "%");
			}
		}});
		bar.parents('.vc_single_bar').find('.vc_bar').velocity({width: maxPrecent}, 1500, 'linear');

	});
}

// "Back to top" arrow
function showScrollToTop() {
	var window_height = $(window).outerHeight();
	var start_scroll = $(window).scrollTop();
	if(start_scroll <= 300) {
		$('#scroll-up').removeClass('show-arrow');
	} else {
		$('#scroll-up').addClass('show-arrow');
	}
	$(window).scroll(function(){
		var scroll_pos = $(window).scrollTop(); 
		if(scroll_pos <= 300) {
			$('#scroll-up').removeClass('show-arrow');
		} else {
			$('#scroll-up').addClass('show-arrow');
		}
	});

	$('#scroll-up').off('click.scrollTop');
	$('#scroll-up').on('click.scrollTop', function(e){
		e.preventDefault();
		$('body').velocity('scroll', {'offset': 0});
	});
}

// VC Row
function bindStretchRowSide() {
	stretchRowSide();
	$(window).on('resize', function(){
		stretchRowSide();
	})
}

function stretchRowSide() {
	$('[data-vc-stretch-side="right"] .extended_bg').each(function(){
		var value = -$(this).parent().offset().left;
		$(this).css('right', value+'px').css('left', 0);
	})
	$('[data-vc-stretch-side="left"] .extended_bg').each(function(){
		var value = -$(this).parent().offset().left;
		$(this).css('left', value+'px').css('right', 0);
	})

}

function gradientRow() {
	$('.veso-gradient-bg').each(function(){
		var $gradient = $(this),
			colors = $gradient.parent().data('gradientColors'),
			orient = $gradient.parent().data('gradientOrient'),
			opacity = $gradient.parent().data('gradientOpacity');

			colors = colors.split(',');
			
			var i,
				j,
				chunk = 2,
				colorArray = [],
				opacityArray = [];

			for (i=0,j=colors.length; i<j; i+=chunk) {
				if(colors.length >= i+chunk) {
					colorArray.push(colors.slice(i,i+chunk));
				} else {
					var lastColor = [colors[i],colors[i]];
					colorArray.push(lastColor);
				}
			}
			for (i=1,j=colors.length; i<=j; i+=1) {
				if(typeof opacity == 'undefinded') {
					opacityArray = [1,1];
				} else {
					opacityArray.push(opacity);
				}
			}
		var granimInstance = new Granim({
		element: $gradient[0],
			direction: orient,
			opacity: opacityArray,
			states : {
				"default-state": {
				   gradients: colorArray,
				}
			}
		});

	});
}

// VC row parallax
function initRowParallax() {
	$('.veso-parallax:not(.veso-parallax-slider)').each(function(){
		var $that = $(this),
			speed = $that.data('vesoParallax'),
			video = null,
			videoStartTime = 0;
		if(speed > 1) {
			speed = speed / 10;
		}
		if($that.data('video')) {
			video = $that.data('video');
			videoStartTime = $that.data('videoStart');
		}
		
		if(speed != 1 || video) {
			if($('.veso-full-pages').length > 0) {
				$that.jarallax({
					speed: speed,
					videoSrc: video,
					videoStartTime: videoStartTime,
					imgElement: '.veso-slider',
					videoPlayOnlyVisible: false,
					loop: 1,
				});
			} else {

				$that.jarallax({
					speed: speed,
					videoSrc: video,
					videoStartTime: videoStartTime,
					imgElement: '.veso-slider',
					loop: 1
				});
			}
		}
	});
}
 
function setColorRow() {
	$('.vc_row').each(function(){
		var $row = $(this),
			color = $row.data('color');
		$row.find('.search-submit svg path').css('fill',color);
	});
}

// WooCommerce
function moveImageCartShop() {
	$('.woocommerce .shop_table_responsive .product-thumbnail').each(function(){
		var $that = $(this);
		var $remove = $that.parent().find('.product-remove');
		if($(window).width() < 768) {
			$that.find('a').appendTo($remove);
		} else {
			$that.parent().find('.product-remove a:not(.remove)').appendTo($that);
		}
	});
}

function setWooCommerce() {
	$('.woocommerce .button').each(function(){
		var $that = $(this);
		if($that.hasClass('checkout')) {
			$that.not('[name=update_cart]').wrap('<div class="btn btn-solid btn-xs btn-dark"></div>');
		} else {
			$that.not('[name=update_cart]').wrap('<div class="btn btn-solid btn-xs btn-light"></div>');
		}
		$that.not('[name=update_cart]').addClass('btn-text').removeClass('button wc-forward');
	});
	$('.woocommerce-product-search input[type=submit]').each(function(){
		var $that = $(this);
		$that.addClass('btn-text').wrap('<div class="btn btn-xs btn-solid btn-dark"></div>');
	});
	$('body.woocommerce:not(.single-product) form').addClass('form-style');

	$('.woocommerce form input:not([type="submit"],.qty), .woocommerce form select, .woocommerce form textarea').not('#coupon_code').wrap('<div class="veso-input"></div>');
	$('.woocommerce .cart-collaterals .cross-sells h2, .woocommerce .cart-collaterals .cart_totals h2, .woocommerce #reviews #comments h2, .woocommerce .related.products h2, .woocommerce div.product .woocommerce-tabs .panel h2, .woocommerce div.product .upsells.products h2').addClass('h4');
}

// Scroll to anchor
function navScrollToAnchor() {
	$('.veso-nav .menu a, .mobile-navbar-overlay .menu-item a, .fixed-nav .menu-item a, a.btn, .slide-nav .veso-slide-content a').on('click', function(e){
		var that_link = $(this),
			link_url = that_link.attr('href'),
			link_anchor = '#'+link_url.replace(/^.*?(#|$)/,'');
		

		if ( link_anchor !== '#' && link_anchor ) {
			e.preventDefault();
			var current_url = window.location.href,
				current_url_hash = window.location.hash,
				new_url = link_url.replace(/#[^#]*$/, ''),
				old_url = current_url.replace(/#[^#]*$/, '');
			// case when url+hash 
			if ( current_url_hash !== '' ) {
				if(new_url == old_url) {
					window.history.pushState('', '', link_url);
					toAnchor(link_anchor);
				} else {
					window.location.href = link_url;
				}

			} else {
				//case when url has no hash
				if ( current_url === link_url ) {
					//scroll
					toAnchor(link_anchor);
				} else {
					var new_url = link_url.replace(/#[^#]*$/, ''),
						old_url = current_url.replace(/#[^#]*$/, '');
					if(new_url == old_url) {
						window.history.pushState('', '', current_url + link_anchor);
						toAnchor(link_anchor);
					} else {
						toAnchor(link_anchor);
						window.location.href = link_url;
					}
				}
			}
		}
	});
}
function scrollToAnchor() {
	var anchor = window.location.hash;
	if ( anchor !== '' ) {
		toAnchor(anchor);
	}
}
function toAnchor(anchor) {
	if(anchor === '#') {
		return false;
	}
	var fixedNavHeight = '';
	if($('.fixed-nav').length > 0) {
		fixedNavHeight = $('.fixed-nav').height();
	} else {
		fixedNavHeight = '0';
	}

	if($(anchor).length > 0) {
		$('.mobile-navbar-helper').removeClass('open-overlay');
		$('.mobile-navbar-overlay').removeClass('show-mobile-nav');
		$("body").removeClass('no-scroll');
		destroyBlazy();
		var scrollOffset = $(anchor).offset().top - fixedNavHeight;
		$("body").velocity("scroll", { offset: scrollOffset, mobileHA: false, easing: [0.645, 0.045, 0.355, 1], duration: 1000, complete:function(){
			initBlazy();

		} });
	}
}

// Text Animation
function textAnimation() {
	setTimeout(function(){
		$('.veso-text-animation').each(function(){
			var $that = $(this),
				element = $that.blast({ delimiter: "word", tag: "span" });

			element.each(function(){
				element = $(this);
				element.wrap('<span class="text-wrap" style=""></span>');
			});
			$that.blast({ delimiter: "character", tag: "span"  }).velocity("transition.shrinkIn",
				{ 
				   display: null,
				   stagger: 35,
				   duration: 500,
				   complete: function() {
					 // Reverse blast
					 $that.blast(false);
				   } 
				}
			);
		});
	}, 3000);
}


// Fullscreen links
function vesoInitFullscreenLinks() {
	$('.veso-fullscreen-links').each(function(){
		var $that = $(this);
		vesoFullscreenLinksResize($that);
		$that.on('mouseenter', '.veso-fullscreen-links-list li', function(){
			$that.find('.veso-fullscreen-links-images li:eq('+$(this).index()+')').addClass('veso-active');
		})

		$that.on('mouseleave', '.veso-fullscreen-links-list li', function(){
			$that.find('.veso-fullscreen-links-images li:eq('+$(this).index()+')').removeClass('veso-active');	
		})

		$that.find('.veso-fullscreen-links-images ul').html($that.find('.veso-fullscreen-links-list li.veso-fullscreen-link-image'));
		$(window).on('resize', function(){
			vesoFullscreenLinksResize($that);
		})
	})
}

function vesoFullscreenLinksResize($that) {
	var bodyHeight = document.body.height,
	mobileNavHeight = $('#mobile-navbar-home').height(),
	windowWidth = $(window).width(),
	containerHeight = bodyHeight - mobileNavHeight,
	navHeight = $('.veso-nav').height();

	if(windowWidth < 1024) {
		$that.css('min-height', containerHeight+'px');
	} else {
		$that.css('min-height', (bodyHeight - navHeight)+'px');
	}
}


$(window).load(function(){
	$('.transition-overlay').velocity({opacity: 0}, {display: 'none', duration: 350, complete: function(){}, delay: 100 });
	initBlazy();
	initVesoPortfolioCarousel();
	vesoInitCountdown();
	initVesoCounter();
	initVesoBlogCarousel();
	initVesoSlider();
	initVesoSlideNav();
	initRowParallax();
	setTimeout(function(){
		scrollToAnchor();
	},500);


	$(".wpb_animate_when_almost_visible:not(.wpb_start_animation)").each(function(){
		var animatedWaypoints = new Waypoint({
			element: this,
			handler: function(direction) {
				$(this.element).addClass("wpb_start_animation animated");
			},
			offset: '85%',
		});
	})

	$(".vc_single_image-wrapper:not(.loaded)").each(function(){
		var animatedImages = new Waypoint({
			element: this,
			handler: function(direction) {
				$(this.element).addClass('loaded');
			},
			offset: '85%',
		});
	})

});

$(document).ready(function(){
	textAnimation();
	// Portfolio
	initPortfolioMasonry();
	showPortfolioHover();
	setHeaderSizePortfolio();
	openPortfolioLightbox();
	initFixedContent();
	// Gallery
	initSingleGallery();
	initVesoPostGallery();
	// VC Elements
	initVesoCarousel();
	initProgressBar();
	changeColorTabs();
	initContactForm();
	setInteractiveBox();
	initVesoSplitSlider();
	// Blog
	initBlogMasonry();
	vesoInitBlogStaticLoadMore();
	// Footer
	setFooter();
	// Navigation
	showBurgerNav();
	openBurgerSubNavOverlay();
	showCommentList();
	initFixedNavigation();
	vesoColorNavSocials();
	// Row
	bindStretchRowSide();
	gradientRow();
	showRowsFullPages();
	setColorRow();
	
	// Scroll events
	showScrollToTop();
	navScrollToAnchor();
	// WooCommerce
	moveImageCartShop();
	setWooCommerce();
	vesoInitFullscreenLinks();
	
	$(window).trigger('refreshLigthboxes');
	$('.hover1, .hover3, .hover4').addClass('hover-not-ready');

	// Add wrapper to select input
	$('select').each(function(){
		var $that = $(this);
		$that.wrap('<div class="select-wrapper"></div>');
	});

	// Blog - hover text
	$('.post-cat-tags .post-tags a').addClass('veso-hover-text');

	// Mega menu
	$('.desktop-menu .menu-item-object-veso_mega_menu > a').on('click', function(e){
		e.preventDefault();
	})
	$('.desktop-menu .menu-item-has-children, .desktop-menu .menu-item-object-veso_mega_menu').hoverIntent({
		over: openSubnav,
		out: hideSubnav,
		timeout: 300,
	});
	$('.desktop-menu > .menu-item-has-children, .desktop-menu .menu-item-object-veso_mega_menu').addClass('first-level');

	// VC Newsletter
	function newsletterRequest(el) {
		$.ajax({
			url: rest_object.api_url + 'email/',
			type: 'POST',
			dataType: 'json',
			data: el.serialize(),
			beforeSend: function ( xhr ) {
				xhr.setRequestHeader( 'X-WP-Nonce', rest_object.api_nonce );
			},
		}).done(function(responseData) {
			if(responseData.status === 'success') {
				el.find('.btn-newsletter').addClass('button-success');
				$('.n-text input').val('');
				$('.n-text input').removeClass('active-input');
				$('.n-text .clear-input').removeClass('clear-input');

				setTimeout(function() {
					$("#n-form .btn-newsletter").removeClass('button-success');
				}, 3000);

			} else {
				if(responseData.status === 'error'){
					$('#n_email').parent().addClass('input-error');
				};
			}
			
		}).fail(function() {

		});
	}
	$('.n-form').each(function(){
		$(this).on('submit', function(e){
			e.preventDefault();
			newsletterRequest($(this));
			$('#n-form .input-error').removeClass('input-error');
		});
	});
});
window.onresize = function(){
	document.body.height = window.innerHeight;
	var bodyHeight = document.body.height,
		mobileNavHeight = $('#mobile-navbar-home').height(),
		windowWidth = $(window).width(),
		containerHeight = bodyHeight - mobileNavHeight;

	if(windowWidth < 1024) {
		$('.veso-split-slider-container .veso-split-slider').css('height',containerHeight);
		$('.veso-split-slider-container .slide').css('height',containerHeight-60);
	}

}
window.onresize();

var sliderTimer;
$(window).on('resize', function(){
	setFooter();
	setInteractiveBox();
	moveImageCartShop();
	clearTimeout(sliderTimer);
	sliderTimer = setTimeout(function(){
		initVesoSlideNav();
	},100)
});
	
$( document ).on( 'added_to_cart', function(){
	setTimeout(function(){
		$('.added_to_cart').addClass('btn').css('display', 'block');
	},100);
})

$('input#place_order').after('<button type="submit">SUBMIT</button>')
$('body.woocommerce-cart .checkout-button').removeClass('button').addClass('btn btn-solid btn-dark btn-md').wrapInner('<span class="btn-text"></span>');
$('body.single-product .button').removeClass('button').addClass('btn btn-solid btn-dark btn-md').wrapInner('<span class="btn-text"></span>');

// Refresh lightboxes
$(window).on('refreshLigthboxes', function(){
	$('.veso_video_lightbox:not(.mfg-ready)').each(function(){

		$(this).addClass('mfg-ready');
		$(this).magnificPopup({
			disableOn: 0,
			type: 'iframe',
			mainClass: 'mfp-fade',
			removalDelay: 160,
			preloader: false,
		});
	});

	$('.gallery:not(.mfg-ready)').each( function(){
		$(this).addClass('mfg-ready');
		$(this).magnificPopup({
			disableOn: 0,
			delegate: 'a',
			type: 'image',
			mainClass: 'mfp-fade',
			removalDelay: 160,
			preloader: false,
			gallery: {
				enabled: true
			}
		});
	})
	
	$('.veso-gallery-element:not(.mfg-ready)').each( function(){
		$(this).addClass('mfg-ready');
		$(this).magnificPopup({
			disableOn: 0,
			delegate: 'a',
			type: 'image',
			mainClass: 'mfp-fade',
			removalDelay: 160,
			preloader: false,
			gallery: {
				enabled: true
			}
		});
	})

	$('.single-image-lightbox:not(.mfg-ready)').each( function(){
		$(this).addClass('mfg-ready');
		$(this).magnificPopup({
			disableOn: 0,
			type: 'image',
			mainClass: 'mfp-fade',
			removalDelay: 160,
			preloader: false,
		});
	})

	$('.gallery-lightbox:not(.mfg-ready)').each(function(){
		$(this).addClass('mfg-ready');
		$(this).magnificPopup({
			delegate: 'a',
			disableOn: 0,
			type: 'image',
			mainClass: 'mfp-fade',
			removalDelay: 300,
			preloader: false,
			gallery: {
				enabled: true,
				navigateByImgClick: true,
				preload: [0,1] // Will preload 0 - before current, and 1 after the current image
			},
		});
	});
	$('.video-link').magnificPopup({
		type:'iframe',
		disableOn: 0,
		mainClass: 'mfp-fade',
		removalDelay: 160,
		preloader: false,
	});
	$('.images-cascade').each(function(){
		var $that = $(this);
		$that.magnificPopup({
			delegate: 'a',
			type:'image', 
			gallery:{
				enabled:true
			},
			disableOn: 0,
			mainClass: 'mfp-fade',
			removalDelay: 160,
			preloader: false,
		});
	});
});

})(jQuery);

