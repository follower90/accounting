vf.require(['App.Menu'], function(Menu) {

	App.Layout = vf.Widget.extend('App.Layout', {

		container: '#app',
		template: 'layout',

		beforeActivate: function (params) {

			if (vf.user) {
				this.widgets.menu = Menu;
				this.widgets.sitePage = params['page'];
			} else {
				vf.Api.get('/api.php?method=User.auth', 'json', function (data) {
					vf.user = data;
					this.widgets = {
						menu: Menu
					};

					if (vf.user) {

						this.widgets.sitePage = params['page'];
						this.activate(params);
					}

				}.bind(this));
			}
		}
	});
});