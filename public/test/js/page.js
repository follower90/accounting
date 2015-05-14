vf.widget('Page', {

	container: '.container',
	template: 'page',
	widgets: {
		menu: {
			container: '#menu',
			template: 'menu',
			load: function () {
				this.setTemplateOptions({text: 'some text'});
			}
		}
	},

	load: function () {
		this.setTemplateOptions({name: 'Vasya'});
	}
});
