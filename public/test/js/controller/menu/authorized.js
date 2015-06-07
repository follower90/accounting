app.registerComponent('Menu_Authorized', {

	container: '#menu',
	template: 'menu/authorized',

	beforeActivate: function () {
		this.setTemplateOptions({name: app.site.user.name });
	}
});
