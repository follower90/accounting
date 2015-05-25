App.MenuAuthorized = vf.Widget.extend('App.MenuAuthorized', {

	container: '#menu',
	template: 'menu/authorized',

	beforeActivate: function () {
		this.setTemplateOptions({name: vf.user.name });
	}
});
