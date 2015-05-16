vf.widget('Main', {

	container: '#container',
	template: 'main/new',
	widgets: {
		newEntry: {
			widget: 'NewEntry'
		}
	},

	beforeRender: function () {
		this.setTemplateOptions({name: 'test'});
	}
});
