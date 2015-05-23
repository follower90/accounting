App.Menu = vf.Widget.extend({

	container: '#menu',
	template: 'menu/unathorized',

	beforeActivate: function () {
		if (vf.user) {
			this.template = 'menu/authorized';
			this.setTemplateOptions({name: vf.user.name });
		}
	}
});
