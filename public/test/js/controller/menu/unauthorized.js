App.MenuNotAuthorized = vf.Widget.extend('App.MenuNotAuthorized', {

	container: '#menu',
	template: 'menu/unauthorized',

	domHandlers: {
		login: {
			element: '.btn-login',
			event: 'click',
			callback: 'login'
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
