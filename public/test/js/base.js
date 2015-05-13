(function (vf) {
	vf.widget('Page', {

		container: '.container',
		template: 'page',
		widgets: {
			simple: {
				container: '#menu',
				template: 'menu',
				load: function () {
					this.setTemplateOptions({test: 'How?'});
				}
			}
		},

		load: function () {
			this.setTemplateOptions({name: 'That\'s magic'});
		}

	});
})(vf);
