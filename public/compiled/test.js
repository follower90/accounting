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
			this.widgets[name].inlineWidgets = {};

			var current = this.widgets[name];

			for (var alias in current.widgets) {
				current.inlineWidgets[alias] = vf.utils.extend(vf.utils.extend({}, current), current.widgets[alias]);
			}
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
				vf.modules.Api.get(vf.options.templates + template + '.tpl', 'text/html')
					.response(function(html) {
						callback(html);
					});
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

	get: function (url, type) {
		this._request(url, 'GET');
		this._type = type;
		return this;
	},

	post: function (url, type, params) {
		this._request(url, 'POST', params);
		this._type = type;
		return this;
	},

	response: function(callback) {
		this._callback = callback;
	},

	_request: function(url, type, callback, params) {
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

		xmlHttp.onreadystatechange = function() {
			if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
				if (api._callback) {
						switch(api._type) {
							case 'text/html':
								var parser = new DOMParser();
								var result  = parser.parseFromString(xmlHttp.responseText, "text/html");
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
					widget.load(params);
					widget.render();
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

vf.module('Widget', {

	container: '',
	template: '',
	templateOptions: {},

	setTemplateOptions: function(obj) {
		this.templateOptions = obj;
	},

	load: function() {
	},

	render: function () {
		var container = vf.dom.find1(this.container),
			_w = this;

		if (container) {
			vf.utils.loadTemplate(this.template, function(template) {
				var rendered = vf.utils.render(template.firstChild.innerHTML, _w.templateOptions);
				container.innerHTML = rendered;
				_w.renderInlineWidgets();
			});
		}
	},

	renderInlineWidgets: function() {
		for (var alias in this.inlineWidgets) {
			var widget = this.inlineWidgets[alias];

			if (widget) {
				widget.load();
				widget.render();
			}
		}
	}
});

vf.modules.Router.routes({
	'#/': {page: 'Page'}
});

vf.options.templates = 'public/test/js/templates/';

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
