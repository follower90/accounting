vf.widget('Page', {

	container: '.container',
	template: 'page',
	widgets: {
		menu: {
			container: '#menu',
			template: 'menu',
			load: function () {
				this.setTemplateOptions({text: 'КАКОЙ_ТО_ТАМ_ТЕКСТ'});
			}
		}
	},

	load: function () {
		this.setTemplateOptions({name: 'Василий'});
	}
});
