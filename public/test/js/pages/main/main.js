App.Main = vf.Widget.extend({

	container: '#container',
	template: 'main/main',
	widgets: {
		new: App.NewEntry,
		entries: App.List
	},

	beforeRender: function () {
		this.setTemplateOptions({name: 'test'});
	}
});