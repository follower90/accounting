App.Layout = vf.Widget.extend({

	container: '#app',
	template: 'layout',

	beforeActivate: function (params) {
		if (vf.user) {
			this.widgets = {
				menu: App.Menu,
				sitePage: params['page']
			};
		} else {
			vf.Api.get('/api.php?method=User.auth', 'json', function (data) {
				vf.user = data;
				this.widgets = {
					menu: App.Menu
				};
				if (vf.user) {
					this.widgets.sitePage = params['page'];
					this.activate(params);
				}

			}.bind(this));
		}
	}
});
