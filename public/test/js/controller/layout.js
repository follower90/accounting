vf.registerComponent('Layout', {

	container: '#app',
	template: 'layout',

	beforeActivate: function (params) {

		if (params['action'] == 'logout') {
			this.logout();
			return false;
		}

		this.components = {};

		if (vf.site.user) {
			this.components.menu = 'Menu_Authorized';
			this.components.sitePage = params['page'];
		} else {
			this.components.menu = 'Menu_NotAuthorized';

			if (!params.ready) {
				vf.module('Api').get('/api.php?method=User.auth', 'json', function (data) {
					vf.site.user = data.response;
					if (vf.site.user) {
						this.components.menu = 'Menu_Authorized';
						this.components.sitePage = params['page'];
					} else {
						this.components.menu = 'Menu_NotAuthorized';
					}

					params.ready = true;
					this.activate(params);
				}.bind(this));
			}
		}
	},

	logout: function () {
		vf.module('Api').get('/api.php?method=User.logout', 'json', function () {
			vf.site.user = null;
			vf.site.gotoPage('/');
		});
	}
});