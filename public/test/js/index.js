var App = {};

App.Router = vf.Router.extend({
	routes: {
		'#/': {page: App.Layout, params: {page: App.Main}},
		'#/profile': {page: App.Layout, params: {page: App.Profile}}
	}
});

vf.registerOption('templates', 'public/test/js/templates/');

window.onload = function () {
	App.Router.run();
};