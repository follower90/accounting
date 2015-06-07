app.registerComponent('Edit_Profile', {

	container: '#edit-profile',
	template: 'profile/form',

	beforeRender: function () {
		this.setTemplateOptions({name: app.site.user.name, login: app.site.user.login });
	}
});
