app.registerComponent('Main_Page', {

	container: '#container',
	template: 'main/main',
	components: {
		new: 'New_Entry',
		entries: 'Entry_List'
	},

	beforeRender: function () {
	}
});
