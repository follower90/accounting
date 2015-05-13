(function () {
	var vf = {

		widgets: {},
		modules: {},
		templates: {},

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

			loadTemplate: function(template) {
				var html = vf.modules.Api.get('public/test/js/templates/' + template + '.tpl'),
					stringContainingXMLSource = html.responseText;

				var parser = new DOMParser();
				dom = parser.parseFromString(stringContainingXMLSource, "application/xml");
				console.log(dom.documentElement.innerHTML);
				return dom.documentElement.innerHTML;

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

(function (vf) {
	vf.module('Api', {

		get: function (url) {
			var xmlHttp = new XMLHttpRequest();
			xmlHttp.open('GET', url, false);
			xmlHttp.send(null);

			return xmlHttp;
		},

		post: function (url, params) {
			var xmlHttp = new XMLHttpRequest();
			xmlHttp.open('POST', url, false);
			xmlHttp.send(new FormData(params));

			return xmlHttp;
		}
	});
})(vf);

(function (vf) {
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
			if (url == '#/') {
				url = '';
			}

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

})(vf);

window.onload = function () {
	vf.modules.Router.run();
};

(function (vf) {
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
			var container = vf.dom.find1(this.container);
			if (container) {
				var template = vf.utils.loadTemplate(this.template),
					rendered = vf.utils.render(template, this.templateOptions);

				container.innerHTML = rendered;
			}

			this._renderInlineWidgets();
		},

		_renderInlineWidgets: function() {
			for (var alias in this.inlineWidgets) {
				var widget = this.inlineWidgets[alias];

				if (widget) {
					widget.load();
					widget.render();
				}
			}
		}
	});
})(vf);

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

vf.modules.Router.routes({
	'': {page: 'Page'}
});
