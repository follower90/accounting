var App = {};

vf.require(['App.Router'], function(Router) {

	vf.registerOption('templates', 'public/test/templates/');

	window.onload = function () {
		Router.run();
	};

});
