vf.widget('Menu', {

	container: '#menu',
	template: 'menu',

	beforeRender: function () {
		this.setTemplateOptions({name: 'Vitaliy Malyshev'});
	}
});
