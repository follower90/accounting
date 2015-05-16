vf.widget('Main', {

	container: '#container',
	template: 'main/main',
	widgets: {
		newEntry: {
			widget: 'NewEntry'
		}
	},

	beforeRender: function () {
		this.setTemplateOptions({name: 'test'});
	}
});
