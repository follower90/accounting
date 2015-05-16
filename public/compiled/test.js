(function () {
	var vf = {

		widgets: {},
		modules: {},
		options: {},

		module: function (name, component) {
			this.modules[name] = component;
		},

		widget: function (name, widget) {
			this.widgets[name] = vf.utils.extend(vf.utils.extend({}, vf.modules.Widget), widget);
		},

		error: function(text) {
			console.error('vfDebugger: ' + text);
		},

		utils: {
			extend: function (obj1, obj2) {
				for (var p in obj2) {
					try {
						if (obj2[p].constructor == Object) {
							obj1[p] = vf.utils.extend(obj1[p], obj2[p]);
						} else {
							obj1[p] = obj2[p];
						}
					} catch (e) {
						obj1[p] = obj2[p];
					}
				}

				return obj1;
			},

			loadTemplate: function(template, callback) {
				return vf.modules.Api.get(vf.options.templates + template + '.tpl', 'text/html', callback);
			},

			render: function (template, vars) {
				for (var i in vars) {
					template = template.replace('{{' + i + '}}', vars[i]);
				}

				return template;
			}
		},

		dom: {
			find: function (query) {
				return window.document.querySelectorAll(query);
			},

			find1: function (query) {
				return window.document.querySelector(query);
			}
		}
	};

	window.vf = vf;
})();

vf.module('Api', {
	get: function (url, type, callback) {
		this._api().get(url, type, callback);
	},
	post:  function (url, type, params, callback) {
		this._api().post(url, type, params, callback);
	},

	_api: function() {
		var Api = function () {

			return {

				get: function (url, type, callback) {
					this._request(url, 'GET');
					this._callback = callback;
					this._type = type;
					return this;
				},

				post: function (url, type, params, callback) {
					this._request(url, 'POST', params);
					this._callback = callback;
					this._type = type;
					return this;
				},

				_request: function (url, type, callback, params) {
					var xmlHttp = new XMLHttpRequest(),
						api = this;

					switch (type) {
						case 'GET':
							xmlHttp.open('GET', url, true);
							xmlHttp.send(null);
							break;

						case 'POST':
							xmlHttp.open('POST', url, true);
							xmlHttp.send(new FormData(params));
							break;
					}

					xmlHttp.onreadystatechange = function () {
						if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
							if (api._callback) {
								switch (api._type) {
									case 'text/html':
										var parser = new DOMParser(),
											dom = parser.parseFromString(xmlHttp.responseText, "text/html"),
											result = dom.body.innerHTML;
										break;
									default:
										result = xmlHttp;
										break;
								}

								api._result = api._callback(result);
							}
						}
					};
				}
			}
		};

		return new Api();
	}
});

vf.module('Event', {
	events: {},
	register: function (event, callback) {
		this.events.push({
			alias: event,
			callback: callback
		});
	},

	trigger: function(event) {
		for (var i in this.events) {
			if (event == events[i].alias) {
				events[i].callback();
				return;
			}
		}
	}
});

vf.module('Router', {
	routesMap: {},

	routes: function (map) {
		this.routesMap = vf.utils.extend(this.routesMap, map);
	},

	run: function () {
		for (var url in this.routesMap) {

			var args = this._matches(url, window.location.hash);

			if (args) {
				var route = this.routesMap[url];

				var params = route.params || {},
					widget = vf.widgets[route.page];

				params = vf.utils.extend(args, params);

				if (widget) {
					widget.activate(params);
				} else {
					vf.error('Widget: ' + route.page + ' not found');
				}
			}
		}
	},

	_matches: function (patt, url) {
		if (url == '') url = '#/';

		var parts = patt.split('/'),
			urlParts = url.split('/'),
			args = [];

		if (parts.length != urlParts.length) {
			return false;
		}

		for (var i = 0; i < parts.length; i++) {
			if (parts[i] != urlParts[i]) {
				if (parts[i][0] != ':') {
					return false;
				}

				var param = parts[i].slice(1);
				args[param] = urlParts[i];
			}
		}

		return args;
	}
});


window.onload = function () {
	vf.modules.Router.run();
};

window.onhashchange = function() {
	vf.modules.Router.run();
};
vf.module('Widget', {

	container: '',
	template: '',
	dom: false,
	templateOptions: {},

	setTemplateOptions: function(obj) {
		this.templateOptions = obj;
		return this;
	},

	beforeRender: function(params) {
	},

	render: function() {
		var container = vf.dom.find1(this.container);
		container.innerHTML = vf.utils.render(this.dom, this.templateOptions);
	},

	load: function() {
		this.beforeRender(this.params);
		this.render();
		this.afterRender();
	},

	afterRender: function() {

	},

	activate: function(params) {
		this.params = params;
		if (this.dom) {
			this.load();
			this.renderInlineWidgets();
		} else {
			this.loadTemplate();
		}
	},

	loadTemplate: function (params) {
		vf.utils.loadTemplate(this.template, function(template) {
			this.dom = template;
			this.load(this.params);
			this.renderInlineWidgets();
		}.bind(this));
	},

	renderInlineWidgets: function() {

		this.inlineWidgets = {};

		for (var alias in this.widgets) {
			var inlineWidget = this.widgets[alias];

			if (inlineWidget.widget) {
				widget = vf.widgets[inlineWidget.widget];
				this.inlineWidgets[alias] = widget;
			} else {
				this.inlineWidgets[alias] = vf.utils.extend(vf.utils.extend({}, vf.modules.Widget), inlineWidget);
			}
		}

		for (var w in this.inlineWidgets) {
			var widget = this.inlineWidgets[w];

			if (widget) {
				widget.activate();
			}
		}
	}
});

vf.modules.Router.routes({
	'#/': {page: 'Layout', params: {page: 'Main'}},
	'#/profile': {page: 'Layout',  params: {page: 'Profile'}}
});

vf.options.templates = 'public/test/js/templates/';

vf.widget('Layout', {

	container: '#app',
	template: 'layout',
	widgets: {
		menu: {
			widget: 'Menu'
		},
		sitePage: {}
	},

	beforeRender: function (params) {
		this.widgets.sitePage = {
			widget: params['page']
		};
	}
});

vf.widget('Menu', {

	container: '#menu',
	template: 'menu',

	beforeRender: function () {
		this.setTemplateOptions({name: 'Vitaliy Malyshev'});
	}
});

vf.widget('Main', {

	container: '#container',
	template: 'main/new',
	widgets: {
		newEntry: {
			widget: 'NewEntry'
		}
	},

	beforeRender: function () {
		this.setTemplateOptions({name: 'test'});
	}
});

vf.widget('NewEntry', {

	container: '#new_entry',
	template: 'main/new_entry',

	beforeRender: function () {

	}
});

vf.widget('EditProfile', {

	container: '#edit-profile',
	template: 'profile/form',

	beforeRender: function () {
		this.setTemplateOptions({name: 'Vitaliy Malyshev', login: 'follower'});
	}
});

vf.widget('Profile', {

	container: '#container',
	template: 'profile/profile',
	widgets: {
		editProfile: {
			widget: 'EditProfile'
		}
	}
});
