vf.widget('Layout', {

	container: '#app',
	template: 'layout',
	widgets: {
		menu: {
			widget: 'Menu'
		},
		sitePage: {}
	},

	beforeRender: function (params) {
		this.widgets.sitePage = {
			widget: params['page']
		};
	}
});
