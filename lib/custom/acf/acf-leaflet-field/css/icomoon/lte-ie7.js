/* Load this script using conditional IE comments if you need to support IE 7 and IE 6. */

window.onload = function() {
	function addIcon(el, entity) {
		var html = el.innerHTML;
		el.innerHTML = '<span style="font-family: \'icomoon\'">' + entity + '</span>' + html;
	}
	var icons = {
			'icon-location' : '&#xe000;',
			'icon-location-2' : '&#xe001;',
			'icon-compass' : '&#xe002;',
			'icon-tag' : '&#xe003;',
			'icon-info' : '&#xe004;',
			'icon-info-2' : '&#xe005;',
			'icon-share' : '&#xe006;',
			'icon-cancel-circle' : '&#xe007;',
			'icon-close' : '&#xe008;',
			'icon-blocked' : '&#xe009;',
			'icon-comment-alt2-fill' : '&#xe00a;',
			'icon-x-altx-alt' : '&#xe00b;',
			'icon-map-pin-alt' : '&#xe00c;',
			'icon-map-pin-fill' : '&#xe00d;',
			'icon-map-pin-stroke' : '&#xe00e;',
			'icon-target' : '&#xe00f;',
			'icon-x' : '&#xe010;',
			'icon-denied' : '&#xe011;',
			'icon-compass-2' : '&#xe012;',
			'icon-compass-3' : '&#xe013;',
			'icon-target-2' : '&#xe014;',
			'icon-tags' : '&#xe015;',
			'icon-location-3' : '&#xe016;',
			'icon-tag-2' : '&#xe017;',
			'icon-cancel' : '&#xe018;',
			'icon-share-2' : '&#xe019;',
			'icon-reload' : '&#xe01a;'
		},
		els = document.getElementsByTagName('*'),
		i, attr, html, c, el;
	for (i = 0; ; i += 1) {
		el = els[i];
		if(!el) {
			break;
		}
		attr = el.getAttribute('data-icon');
		if (attr) {
			addIcon(el, attr);
		}
		c = el.className;
		c = c.match(/icon-[^\s'"]+/);
		if (c && icons[c[0]]) {
			addIcon(el, icons[c[0]]);
		}
	}
};