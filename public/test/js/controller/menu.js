App.Menu = vf.Widget.extend('App.Menu', {

	container: '#menu',
	template: 'menu/unauthorized',
	autoRender: false,

	domHandlers: {
		login: {
			element: '.btn-login',
			event: 'click',
			callback: 'login'
		}
	},

	beforeActivate: function () {
		this.dom = null;
		if (vf.user) {
			this.template = 'menu/authorized';
			this.setTemplateOptions({name: vf.user.name });
		} else {
			this.template = 'menu/unauthorized';
		}
	},

	login: function() {
		var _ = this;

		var params = {
			name: _.find1('.input-name').value,
			pass: _.find1('.input-pass').value
		};

		vf.Api.post('/api.php?method=User.login', 'json', params, function () {
			vf.Api.get('/api.php?method=User.auth', 'json', function () {
				App.Router.update();
			});
		});
	}
});
