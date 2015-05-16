vf.widget('EditProfile', {

	container: '#edit-profile',
	template: 'profile/form',

	beforeRender: function () {
		this.setTemplateOptions({name: vf.user.name, login: vf.user.login });
	}
});
