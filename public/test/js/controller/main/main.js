vf.require(['App.List', 'App.NewEntry'], function(List, NewEntry) {

	App.Main = vf.Widget.extend('App.Main', {

		container: '#container',
		template: 'main/main',
		widgets: {
			new: NewEntry,
			entries: List
		},

		beforeRender: function () {
		}
	});
});
