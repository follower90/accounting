vf.registerComponent('Menu_Authorized', {

	container: '#menu',
	template: 'menu/authorized',

	beforeActivate: function () {
		this.setTemplateOptions({name: vf.site.user.name });
	}
});
