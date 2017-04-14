'use strict';

window.eoxiaJS = {};
window.taskManagerWpshop = {};

window.eoxiaJS.init = function() {
	window.eoxiaJS.loadScripts();
	window.eoxiaJS.initArrayForm();
};

window.eoxiaJS.loadScripts = function() {
	var key;
	for ( key in window.taskManagerWpshop ) {
		window.taskManagerWpshop[key].init();
	}
};

window.eoxiaJS.initArrayForm = function() {
	 window.eoxiaJS.arrayForm.init();
};

jQuery( document ).ready( window.eoxiaJS.init );
