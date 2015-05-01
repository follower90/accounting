$.fn.xcharts = function (params) {

	var xcharts = function (elem) {
		this.elem = elem;
		this.data = '';
		this.labels = '';
		this.css = '';

		this.linear = function (params) {
			var total = 0, keys = [], data = params.data;
			this.css += '.line-xchart-elem { float: left; height: inherit; }' +
			'.line-xchart-container { display: table; height: ' + params.height + 'px; width: 100%; margin-bottom: 15px; }' +
			'.line-xchart-label { height: 15px; margin-top: 10px; font-size: 13px; font-family: Arial, sans-serif; }' +
			'.line-xchart-label i { width: 10px; height: 10px; margin-right: 10px; display: inline-block; }';

			for (var key in data) {
				keys.push(key);
				total += parseFloat(data[key]);
			}

			for (var key in data) {
				var color = this.getRandomColor();
				this.data += '<div class="line-xchart-elem" style="width:' + data[key] / total * 100 + '%;background:' +
				color + '; "></div>';

				this.labels += '<div class="line-xchart-label"><i style="background: ' + color + ';"></i>' +
				key + ': ' + data[key] + ' грн. (' + Math.floor(data[key] / total * 100 * 10) / 10 + '%)</div>';
			}

			this.elem.css('display', 'table');
			this.elem.html('<div class="line-xchart-container">' + this.data + '</div>' + this.labels);
			this.applyStyles();

			return this.elem;
		};

		this.applyStyles = function () {
			var style = document.createElement("style");
			style.innerHTML = this.css;
			document.head.appendChild(style);
		};

		this.getRandomColor = function () {
			var letters = '0123456789ABCDEF'.split('');
			var color = '#';
			for (var i = 0; i < 6; i++) {
				color += letters[Math.floor(Math.random() * 16)];
			}
			return color;
		};

		this.draw = function () {
			switch (params.type) {
				case 'linear':
					this.linear(params);
					return this.elem;
					break;
				default:
					console.error('xcharts init error');
			}
		};
	};

	var charts = new xcharts(this, params);
	return charts.draw();
};
