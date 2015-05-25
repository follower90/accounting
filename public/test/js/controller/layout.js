vf.require(['App.MenuAuthorized', 'App.MenuNotAuthorized'], function(MenuAuthorized, MenuNotAuthorized) {

	App.Layout = vf.Widget.extend('App.Layout', {

		container: '#app',
		template: 'layout',

		beforeActivate: function (params) {

			if (params['action'] == 'logout') {
				this.logout();
				return false;
			}

			this.widgets = {};

			if (vf.user) {
				this.widgets.menu = MenuAuthorized;
				this.widgets.sitePage = params['page'];
				this.widgets.menu.activate()
			} else {
				vf.Api.get('/api.php?method=User.auth', 'json', function (data) {
					vf.user = data;
					if (vf.user) {
						this.widgets.menu = MenuAuthorized;
						this.widgets.sitePage = params['page'];
						this.activate(params);
					} else {
						this.widgets.menu = MenuNotAuthorized;
						this.widgets.menu.activate();
					}

				}.bind(this));
			}
		},

		logout: function() {
			vf.Api.get('/api.php?method=User.logout', 'json', function () {
				vf.user = null;
				vf.site.gotoPage('/');
			});
		}
	});
});