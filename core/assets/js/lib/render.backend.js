window.taskManagerWpshop.render = {};

window.taskManagerWpshop.render.init = function() {
	window.taskManagerWpshop.render.event();
};

window.taskManagerWpshop.render.event = function() {};

window.taskManagerWpshop.render.callRenderChanged = function() {
	var key = undefined;

	for ( key in window.taskManagerWpshop ) {
		if ( window.taskManagerWpshop[key].renderChanged ) {
			window.taskManagerWpshop[key].renderChanged();
		}
	}
};
