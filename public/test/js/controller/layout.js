vf.require(['App.Menu'], function(Menu) {

	App.Layout = vf.Widget.extend('App.Layout', {

		container: '#app',
		template: 'layout',

		beforeActivate: function (params) {

			if (params['action'] == 'logout') {
				this.logout();
				return false;
			}

			this.widgets = {};
			this.widgets.menu = Menu;

			if (vf.user) {
				this.widgets.sitePage = params['page'];
				this.widgets.menu.render();
			} else {
				vf.Api.get('/api.php?method=User.auth', 'json', function (data) {
					vf.user = data;
					if (vf.user) {
						this.widgets.sitePage = params['page'];
						this.activate(params);
					}

					this.widgets.menu.activate();
					this.widgets.menu.render();
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