window.preventDefault = event => {
	const e = event || window.event;
	if (e.preventDefault) {
		e.preventDefault();
	}
	e.returnValue = false;
};

window.preventDefaultForScrollKeys = event => {
	let shouldPrevent = false;
	const scrollKeys = { 37: 1, 38: 1, 39: 1, 40: 1 };
	if (scrollKeys[event.keyCode]) {
		window.preventDefault(event);
	} else {
		shouldPrevent = true;
	}
	return shouldPrevent;
};

window.disableScroll = () => {
	if (window.addEventListener) {
		window.addEventListener('DOMMouseScroll', window.preventDefault, false);
	}
	window.onwheel = window.preventDefault; // modern standard
	window.onmousewheel = document.onmousewheel = window.preventDefault; // older browsers, IE
	window.ontouchmove = window.preventDefault; // mobile
	document.onkeydown = window.preventDefaultForScrollKeys;
};

jQuery.event.special.scrolldelta = {
	delegateType: 'scroll',
	bindType: 'scroll',
	handle(event) {
		const handleObj = event.handleObj;
		const targetData = jQuery.data(event.target);
		let ret = null;
		const elem = event.target;
		const isDoc = elem === document;
		const oldTop = targetData.top || 0;
		const oldLeft = targetData.left || 0;
		targetData.top = isDoc ? elem.documentElement.scrollTop + elem.body.scrollTop : elem.scrollTop;
		targetData.left = isDoc
			? elem.documentElement.scrollLeft + elem.body.scrollLeft
			: elem.scrollLeft;
		event.scrollTopDelta = targetData.top - oldTop;
		event.scrollTop = targetData.top;
		event.scrollLeftDelta = targetData.left - oldLeft;
		event.scrollLeft = targetData.left;
		event.type = handleObj.origType;
		ret = handleObj.handler.apply(this, arguments);
		event.type = handleObj.type;
		return ret;
	}
};


window.getScrollTop = () =>
	window.scrollY
	|| window.pageYOffset
	|| document.documentElement.scrollTop;

window.findMenuChildren = target => {
	const $target = jQuery(target);
	const returnObj = [];
	const children = $target.children('.menu__item');
	jQuery.each(children, (i, el) => {
		const grandchildren = jQuery(el).find('.menu__item');
		const elObj = {
			el,
			children: grandchildren.length >= 0
				? window.findMenuChildren(jQuery(el).find('ul')[0])
				: undefined
		};
		returnObj.push(elObj);
	});
	return returnObj;
};

window.createMenuItem = (item, depth = 0) => {
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
			returnStr += window.createMenuItem(el, depth + 1);
		});
	}
	return returnStr;
};
