app.registerComponent('Layout', {

	container: '#app',
	template: 'layout',

	beforeActivate: function (params) {

		if (params['action'] == 'logout') {
			this.logout();
			return false;
		}

		this.components = {};

		if (app.site.user) {
			this.components.menu = 'Menu_Authorized';
			this.components.sitePage = params['page'];
		} else {
			this.components.menu = 'Menu_NotAuthorized';
			if (!params.ready) {
				app.module('Api').get('/api.php?method=User.auth', 'json', function (data) {
					app.site.user = data.response;
					if (app.site.user) {
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
		app.module('Api').get('/api.php?method=User.logout', 'json', function () {
			app.site.user = null;
			app.site.gotoPage('/');
		});
	}
});