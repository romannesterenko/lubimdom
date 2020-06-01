InitTabsScroll = function(){
	$('.arrow_scroll:not(.arrow_scroll_init)').scrollTab();
}

ResizeScrollTabs = function() {
	var scrollTabs = $('.arrow_scroll_init');

	if(scrollTabs.length) {
		scrollTabs.each(function(i, scrollTab){
			var _scrollTab = $(scrollTab);
			_scrollTab.data('scrollTabOptions').resize();
		});
	}
}

$(document).ready(function(){
	InitTabsScroll();
});

$(window).on('resize', function(){
	if(window.scrollTabsTimeout !== undefined) {
		clearTimeout(window.scrollTabsTimeout);
	}
	
	window.scrollTabsTimeout = setTimeout(
		ResizeScrollTabs,
		20
	);
});

$.fn.scrollTab = function( options ){
	function _scrollTab(element, options) {
		var _scrollTab = $(element);
		var tabs_wrapper = _scrollTab.find(options.tabs_wrapper);
		var tabs = tabs_wrapper.find('> li');

		var arrow_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="8" viewBox="0 0 12 8">'+
							'<rect width="12" height="8" fill="#333" fill-opacity="0" />'+
							'<path d="M1015.69,507.693a0.986,0.986,0,0,1-1.4,0l-4.31-4.316-4.3,4.316a0.993,0.993,0,0,1-1.4-1.408l4.99-5.009a1.026,1.026,0,0,1,1.43,0l4.99,5.009A0.993,0.993,0,0,1,1015.69,507.693Z" fill="#333" transform="translate(-1004 -501)"/>'+
						'</svg>';
		var arrows_wrapper = '<div class="arrows_wrapper">'+
								'<div class="arrow arrow_left colored_theme_hover_text">'+arrow_svg+'</div>'+
								'<div class="arrow arrow_right colored_theme_hover_text">'+arrow_svg+'</div>'+
							'</div>';

		var arrows = $(arrows_wrapper).insertAfter(tabs_wrapper);
		var arrow_left = arrows.find('.arrow_left');
		var arrow_right = arrows.find('.arrow_right');

		var thisOoptions = $.extend({}, options);

		thisOoptions.scrollTab = _scrollTab;
		thisOoptions.wrapper = tabs_wrapper;
		thisOoptions.tabs = tabs;
		thisOoptions.arrows = {};
		thisOoptions.arrows.wrapper = arrows;
		thisOoptions.arrows.arrow_left = arrow_left;
		thisOoptions.arrows.arrow_right = arrow_right;
		thisOoptions.arrows.arrow_width = arrow_right[0].getBoundingClientRect().width;

		if(thisOoptions.linked_tabs !== undefined && thisOoptions.linked_tabs.length && thisOoptions.linked_tabs.data('scrollTabOptions') !== undefined) {
			thisOoptions.linked_options = thisOoptions.linked_tabs.data('scrollTabOptions');
			thisOoptions.linked_options.linked_options = thisOoptions;
		}

		if(options.arrows_css) {
			thisOoptions.arrows.arrow_left.css(options.arrows_css);
			thisOoptions.arrows.arrow_right.css(options.arrows_css);
		}

		thisOoptions.GetSummWidth = function(elements){
			elements = $(elements);
			var result = 0;
			if(elements.length) {
				elements.each(function(i, element){
					var _element = $(element);
					var elementWidth = _element.outerWidth(true) + thisOoptions.width_grow;
					elementWidth = Math.ceil(elementWidth*10)/10;
					_element.data( 'leftBound',  result );
					result += elementWidth;
					_element.data( 'rightBound',  result );
				});
			}
			return result;
		}

		var tabs_width = thisOoptions.GetSummWidth(thisOoptions.tabs);
		thisOoptions.tabs_width = tabs_width;
		thisOoptions.minTranslate = (thisOoptions.width < thisOoptions.tabs_width ? thisOoptions.width - thisOoptions.tabs_width - 1 : 0);

		if(thisOoptions.tabs_width) {
			thisOoptions.wrapper.css({
				'white-space': 'nowrap',
				'min-width': 'auto',
			});
		};
		thisOoptions.scrollTab.css({
			'overflow': 'hidden',
			'position': 'relative',
		});

		thisOoptions.checkArrows = function(translate){
			if(translate === undefined) {
				translate = thisOoptions.translate;
			}

			if (translate <= 1) {
				return;
			}
			
			if(translate >= thisOoptions.maxTranslate) {
				thisOoptions.arrows.arrow_left.addClass('disabled');
				thisOoptions.arrows.arrow_right.removeClass('disabled');
			} else if(translate <= thisOoptions.minTranslate) {
				thisOoptions.arrows.arrow_right.addClass('disabled');
				thisOoptions.arrows.arrow_left.removeClass('disabled');
			} else {
				thisOoptions.arrows.arrow_left.removeClass('disabled');
				thisOoptions.arrows.arrow_right.removeClass('disabled');
			}

		}

		thisOoptions.directScroll = function(distance, delay){
			if(delay === undefined) {
				delay = 5;
			}
			clearInterval(thisOoptions.timerMoveDirect);
			var newTranslate = thisOoptions.translate + distance;

			if(newTranslate > thisOoptions.maxTranslate) {
				newTranslate = thisOoptions.maxTranslate;
			} else if(newTranslate < thisOoptions.minTranslate) {
				newTranslate = thisOoptions.minTranslate;
			}

			if(delay == 0) {
				thisOoptions.translate = newTranslate;
				thisOoptions.wrapper.css({
					'transform': 'translateX('+thisOoptions.translate+'px)',
				});
			} else {
				thisOoptions.timerMoveDirect = setInterval(
					function() {
						if( (distance < 0 && thisOoptions.translate <= newTranslate) || (distance > 0 && thisOoptions.translate >= newTranslate) ) {
							clearInterval(thisOoptions.timerMoveDirect);
						}

						if(thisOoptions.translate < newTranslate) {
							thisOoptions.translate++;
						} else {
							thisOoptions.translate--;
						}

						thisOoptions.wrapper.css({
							'transform': 'translateX('+thisOoptions.translate+'px)',
						});
					},
					delay
				);
			}
			thisOoptions.checkArrows(newTranslate);
		};
		
		thisOoptions.addArrowsEvents = function() {
			thisOoptions.arrows.arrow_right.on('mouseenter', function() {
				thisOoptions.arrows.arrow_left.removeClass('disabled');
				thisOoptions.tabs_width = thisOoptions.GetSummWidth(thisOoptions.tabs);
				thisOoptions.minTranslate = (thisOoptions.width < thisOoptions.tabs_width ? thisOoptions.width - thisOoptions.tabs_width - 1 : 0);
				thisOoptions.timerMoveLeft = setInterval(
					function() {
						if( thisOoptions.translate < thisOoptions.minTranslate ){
							clearInterval(thisOoptions.timerMoveLeft);
							thisOoptions.arrows.arrow_right.addClass('disabled');
						} else {
							thisOoptions.translate -= thisOoptions.translateSpeed;
							thisOoptions.wrapper.css({
								'transform': 'translateX('+thisOoptions.translate+'px)',
							});
						}
					},
					10
				);
			});

			thisOoptions.arrows.arrow_right.on('mouseleave', function() {
				clearInterval(thisOoptions.timerMoveLeft);
			});

			thisOoptions.arrows.arrow_right.on('click', function() {
				thisOoptions.directScroll(-thisOoptions.directTranslate);
				thisOoptions.arrows.arrow_left.removeClass('disabled');
			});

			thisOoptions.arrows.arrow_right.on('touchend', function() {
				setTimeout(function() {
					clearInterval(thisOoptions.timerMoveLeft);
				}, 1);
			});

			thisOoptions.arrows.arrow_left.on('mouseenter', function() {
				thisOoptions.tabs_width = thisOoptions.GetSummWidth(thisOoptions.tabs);
				thisOoptions.minTranslate = (thisOoptions.width < thisOoptions.tabs_width ? thisOoptions.width - thisOoptions.tabs_width - 1 : 0);
				thisOoptions.arrows.arrow_right.removeClass('disabled');
				thisOoptions.timerMoveRight = setInterval(
					function() {
						if(thisOoptions.translate >= thisOoptions.maxTranslate){
							clearInterval(thisOoptions.timerMoveRight);
							thisOoptions.arrows.arrow_left.addClass('disabled');
						} else {
							thisOoptions.translate += thisOoptions.translateSpeed;
							thisOoptions.wrapper.css({
								'transform': 'translateX('+thisOoptions.translate+'px)',
							});
						}
					},
					10
				);
			});

			thisOoptions.arrows.arrow_left.on('mouseleave', function() {
				clearInterval(thisOoptions.timerMoveRight);
			});

			thisOoptions.arrows.arrow_left.on('click', function() {
				thisOoptions.directScroll(thisOoptions.directTranslate);
				thisOoptions.arrows.arrow_right.removeClass('disabled');
			});

			thisOoptions.arrows.arrow_left.on('touchend', function() {
				setTimeout(function() {
					clearInterval(thisOoptions.timerMoveRight);
				}, 1);
			});
		};

		thisOoptions.addTabsEvents = function() {
			thisOoptions.tabs.on('click', function() {
				var leftScrollBound = thisOoptions.scrollTab[0].getBoundingClientRect().left;
				var rightScrollBound = thisOoptions.scrollTab[0].getBoundingClientRect().right;
				var tabBounds = this.getBoundingClientRect();

				if(tabBounds.left < leftScrollBound) {
					thisOoptions.directScroll(leftScrollBound - tabBounds.left + thisOoptions.arrows.arrow_width, 1);
				} else if(tabBounds.right > rightScrollBound) {
					thisOoptions.directScroll(rightScrollBound - tabBounds.right - thisOoptions.arrows.arrow_width, 1);
				}

				if(thisOoptions.linked_options !== undefined) {
					var this_index = $(this).index();
					var linked_tab = $(thisOoptions.linked_options.tabs[this_index]);
					var linked_tabs = {
						leftScrollBound: thisOoptions.linked_options.scrollTab[0].getBoundingClientRect().left,
						rightScrollBound: thisOoptions.linked_options.scrollTab[0].getBoundingClientRect().right,
						tabBounds: linked_tab[0].getBoundingClientRect(),
					};
					if(linked_tabs.tabBounds.left < linked_tabs.leftScrollBound) {
						thisOoptions.linked_options.directScroll(linked_tabs.leftScrollBound - linked_tabs.tabBounds.left + thisOoptions.linked_options.arrows.arrow_width + 1, 0);
					} else if(linked_tabs.tabBounds.right > linked_tabs.rightScrollBound) {
						thisOoptions.linked_options.directScroll(linked_tabs.rightScrollBound - linked_tabs.tabBounds.right - thisOoptions.linked_options.arrows.arrow_width - 1, 0);
					}
				}

				
			});
		};

		thisOoptions.addWrapperEvents = function() {
			thisOoptions.wrapper.on('touchstart', function(event) {
				thisOoptions.touch.posPrev = event.originalEvent.changedTouches[0].pageX;
				clearInterval(thisOoptions.timerMoveRight);
				clearInterval(thisOoptions.timerMoveLeft);
				clearInterval(thisOoptions.timerMoveDirect);
				thisOoptions.tabs_width = thisOoptions.GetSummWidth(thisOoptions.tabs);
				thisOoptions.minTranslate = (thisOoptions.width < thisOoptions.tabs_width ? thisOoptions.width - thisOoptions.tabs_width - 1 : 0);
			});

			thisOoptions.wrapper.on('touchmove', function(event) {
				thisOoptions.touch.posCurrent = event.originalEvent.changedTouches[0].pageX - thisOoptions.touch.posPrev;
				thisOoptions.directScroll(thisOoptions.touch.posCurrent, 0);
				thisOoptions.touch.posPrev = event.originalEvent.changedTouches[0].pageX;
			});
		};

		thisOoptions.resize = function(){
			if(thisOoptions.onBeforeResize && typeof(thisOoptions.onBeforeResize) == 'function') {
				thisOoptions.onBeforeResize(thisOoptions);
			}
			thisOoptions.width = Math.ceil(thisOoptions.scrollTab[0].getBoundingClientRect().width);
			thisOoptions.tabs_width = thisOoptions.GetSummWidth(thisOoptions.tabs);
			thisOoptions.minTranslate = (thisOoptions.width < thisOoptions.tabs_width ? thisOoptions.width - thisOoptions.tabs_width - 1 : 0);

			if(thisOoptions.onResize && typeof(thisOoptions.onResize) == 'function') {
				thisOoptions.onResize(thisOoptions);
			}

			if(thisOoptions.translate < thisOoptions.minTranslate) {
				thisOoptions.directScroll(thisOoptions.minTranslate - thisOoptions.translate);
			} else if(thisOoptions.translate > thisOoptions.maxTranslate) {
				thisOoptions.directScroll(thisOoptions.maxTranslate - thisOoptions.translate);
			}

			if(thisOoptions.tabs_width < thisOoptions.width) {
				thisOoptions.arrows.wrapper.css('display', 'none');
			} else {
				thisOoptions.arrows.wrapper.css('display', '');
				thisOoptions.arrows.arrow_left.removeClass('disabled');
				thisOoptions.arrows.arrow_right.removeClass('disabled');
				if(thisOoptions.translate >= 0) {
					thisOoptions.arrows.arrow_left.addClass('disabled');
				}
				if(thisOoptions.translate <= thisOoptions.minTranslate) {
					thisOoptions.arrows.arrow_right.addClass('disabled');
				}
			}

			if(thisOoptions.onAfterResize && typeof(thisOoptions.onAfterResize) == 'function') {
				thisOoptions.onAfterResize(thisOoptions);
			}
		};


		_scrollTab.data('scrollTabOptions', thisOoptions );
		_scrollTab.data('scrollTabOptions').addArrowsEvents();
		_scrollTab.data('scrollTabOptions').addTabsEvents();
		_scrollTab.data('scrollTabOptions').addWrapperEvents();
		_scrollTab.addClass('arrow_scroll_init').addClass('swipeignore');
		_scrollTab.data('scrollTabOptions').resize();
		delete thisOoptions;
	}

	var options = $.extend({
		translate: 0,
		translateSpeed: 2,
		directTranslate: 150,
		maxTranslate: 1,
		touch: {},
		arrows_css: false,
		tabs_wrapper: '.nav-tabs',
		onResize: false,
		width_grow: 9,
	}, options);

	var el = $(this);

	if(el.hasClass('arrow_scroll_init'))
		return false;

	el.each(function(i, scrollTab){
		_scrollTab(scrollTab, options);
	});
}