/* eslint no-var: 0*/

import './vendor/webpack.publicPath';

function findMenuChildren(target) {
	const $target = jQuery(target);
	var returnObj = [];
	const children = $target.children('.menu__item');
	jQuery.each(children, (i, el) => {
		const grandchildren = jQuery(el).find('.menu__item');
		const elObj = {
			el,
			children: grandchildren.length >= 0 ? findMenuChildren(jQuery(el).find('ul')[0]) : undefined
		};
		returnObj.push(elObj);
	});
	return returnObj;
}

function createMenuItem(item, depth = 0) {
	let returnStr = '';
	const link = jQuery(item.el).children('a').attr('href');
	let text = jQuery(item.el).children('a').text();
	let i;
	for (i = 0; i < depth; i++) {
		text = '- ${text}';
	}
	returnStr += `<option value="${link}">${text}</option>`;
	if (item.children) {
		jQuery.each(item.children, (index, el) => {
			returnStr += createMenuItem(el, depth + 1);
		});
	}
	return returnStr;
}

jQuery(document).ready(($) => {
	const mainMenuItems = findMenuChildren('.menu--primary .menu__list');
	let options = '<option>- select page -</option>';

	$.each(mainMenuItems, (i, item) => {
		options += createMenuItem(item);
	});
	$('.menu--primary').append(`<select class="menu--primary--mobile">${options}</select>`);
	$(document).on('change', '.menu--primary--mobile', (event) => {
		window.location = event.target.value;
	});
});
