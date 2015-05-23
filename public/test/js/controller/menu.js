App.Menu = vf.Widget.extend('App.Menu', {

	container: '#menu',
	template: 'menu/unauthorized',

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
		var params = {
			name: 'follower',
			pass: 'v6v6v6'
		};

		vf.Api.post('/api.php?method=User.login', 'json', params, function (data) {
			vf.Api.post('/api.php?method=User.auth', 'json', params, function (data) {
				App.Router.update();
			});
		});
	}
});
