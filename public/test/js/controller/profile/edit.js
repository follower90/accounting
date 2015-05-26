vf.registerComponent('Edit_Profile', {

	container: '#edit-profile',
	template: 'profile/form',

	beforeRender: function () {
		this.setTemplateOptions({name: vf.site.user.name, login: vf.site.user.login });
	}
});
