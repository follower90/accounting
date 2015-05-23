App.ListItem = vf.Widget.extend('App.ListItem', {

	container: '#list_items',
	template: 'main/list_item',
	widgets: {
		listItemEdit: {
			template: 'main/list_item_edit'
		}
	},

	beforeRender: function () {
		this.setTemplateOptions({name: 'test'});
	}
});
