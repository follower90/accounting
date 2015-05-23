vf.require(['App.EditProfile'], function(EditProfile) {

	App.Profile = vf.Widget.extend('App.Profile', {
		container: '#container',
		template: 'profile/profile',
		widgets: {
			editProfile: EditProfile
		},

		beforeActivate: function(params) {
			if (!!params && params['action'] == 'logout') {
				vf.Api.get('/api.php?method=User.logout', 'json', function () {
					vf.user = null;
					vf.site.gotoPage('/');
				});
			}
		}
	});
});
