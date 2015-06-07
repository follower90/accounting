app.registerComponent('Menu_NotAuthorized', {

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

		app.module('Api').post('/api.php?method=User.login', 'json', params, function () {
			app.module('Api').get('/api.php?method=User.auth', 'json', function () {
				app.module('Router').update();
			});
		});
	}
});
