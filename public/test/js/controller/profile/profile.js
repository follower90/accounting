app.registerComponent('Profile', {
	container: '#container',
	template: 'profile/profile',
	components: {
		editProfile: 'Edit_Profile'
	},

	beforeActivate: function (params) {
		if (!!params && params['action'] == 'logout') {
			app.module('Api').get('/api.php?method=User.logout', 'json', function () {
				app.site.user = null;
				app.site.gotoPage('/');
			});
		}
	}
});