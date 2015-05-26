vf.registerComponent('Profile', {
	container: '#container',
	template: 'profile/profile',
	components: {
		editProfile: 'Edit_Profile'
	},

	beforeActivate: function (params) {
		if (!!params && params['action'] == 'logout') {
			vf.module('Api').get('/api.php?method=User.logout', 'json', function () {
				vf.site.user = null;
				vf.site.gotoPage('/');
			});
		}
	}
});