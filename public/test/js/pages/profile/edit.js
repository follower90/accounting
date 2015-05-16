vf.widget('EditProfile', {

	container: '#edit-profile',
	template: 'profile/form',

	beforeRender: function () {
		this.setTemplateOptions({name: 'Vitaliy Malyshev', login: 'follower'});
	}
});
