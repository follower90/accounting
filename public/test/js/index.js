app.registerOption('templates', 'public/test/templates/');

window.onload = function () {
	app.module('Router').run();
};
