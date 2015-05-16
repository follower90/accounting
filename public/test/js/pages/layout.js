vf.widget('Layout', {

	container: '#app',
	template: 'layout',

	beforeActivate: function (params) {
		if (vf.user) {
			this.widgets = {
				menu: {
					widget: 'Menu'
				},
				sitePage: {
					widget: params['page']
				}
			};
		} else {
			vf.modules.Api.get('/api/User.auth', 'json', function (data) {
				vf.user = data;
				this.widgets = {
					menu: {
						widget: 'Menu'
					}
				};
				if (vf.user) {
					this.widgets.sitePage = {
						widget: params['page']
					};
				}
				this.activate(params);
			}.bind(this));
		}
	}
});
