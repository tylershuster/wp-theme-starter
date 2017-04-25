/* eslint no-var: 0*/

import './vendor/webpack.publicPath';

import './scripts/objectFitPolyfill.min.js';

import './utils/utils.js';

import 'lazysizes';

// import 'svg.js'; // Uncomment to use SVG.js library

window.headerFixed = false;
window.headerVisible = true;
window.bufferCapacity = 32;
window.headerBuffer = 0;
window.lazyLoads = {};
window.headerOffset = 48;
window.offset = 0;

jQuery(document).ready(($) => {
	window.headerOffset = $('.site__header').height();
	window.headerBuffer = 0;
	window.bufferCapacity = window.headerOffset * 5;

	// On scroll, calculate if head should be fixed
	$(document).on('scrolldelta', event => {
		if (event.scrollTopDelta > 0) { // If view has moved down
			// Check if user has scrolled past head height
			if (
				window.getScrollTop() > window.headerOffset
				&& !window.headerFixed
			) {
				// Hide but fix header
				$('.site__header').addClass('fixed');
				window.headerVisible = false;
				window.headerFixed = true;
			} else if (window.headerFixed && window.headerVisible) {
				// If scrolling down, hide header but keep fixed
				$('.site__header').removeClass('visible');
				window.headerVisible = false;
			}
		} else { // If view has moved up
			// Check if user is within range of the top
			if (window.getScrollTop() < window.headerOffset) {
				// And un-fix the header
				$('.site__header').removeClass('fixed');
				window.headerFixed = false;
				setTimeout(() => {
					// To prevent jumping
					$('.site__header').removeClass('visible');
				}, 500);
			} else if (!window.headerVisible) {
				// If scrolling up, add to the buffer
				window.headerBuffer += event.scrollTopDelta * -1;
				// If buffer capacity has been reached, show the header
				if (window.headerBuffer >= window.bufferCapacity) {
					$('.site__header').addClass('visible');
					window.headerVisible = true;
				}
			}
		}
	});

	// Reset head offset on window resize
	$(window).on('resize', () => {
		window.headerOffset = $('.site__header').height();
	});

	// Prevent widows
	$('p, h2, h3').each(() => {
		var wordArray = $(this).text().split(' ');
		if (wordArray.length > 1) {
			wordArray[wordArray.length - 2] += `&nbsp;${wordArray[wordArray.length - 1]}`;
			wordArray.pop();
			$(this).html(wordArray.join(' '));
		}
	});

	// Assemble the select menu
	const mainMenuItems = window.findMenuChildren('.menu--primary .menu__list--level-0');
	let options = '<option>- select page -</option>';

	$.each(mainMenuItems, (i, item) => {
		options += window.createMenuItem(item);
	});
	$('nav.menu--primary').append(`<select class="menu--primary--mobile">${options}</select>`);
	$(document).on('change', '.menu--primary--mobile', (event) => {
		window.location = event.target.value;
	});
});
