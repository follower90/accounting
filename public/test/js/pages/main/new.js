vf.widget('NewEntry', {

	container: '#new_entry',
	template: 'main/new',
	categories: false,
	widgets: {
		categories: {
			container: '.select-categories',
			dom: '<select class="form-control" id="type" name="types">{{options}}</select>',
			widget: 'Select_Box'
		}
	},

	domHandlers: {
		new: {
			element: '.new-entry',
			event: 'click',
			callback: 'newEntry'
		}
	},

	beforeRender: function () {
		if (!this.categories) {
			this
				.inlineWidgets
				.categories
				.setTemplateOptions({data: [{id: 0, name: 'Loading...'}]});

			vf.modules.Api.get('/api.php?method=Category.list', 'json', function (data) {
				this
					.inlineWidgets
					.categories
					.setTemplateOptions({data: data}).load();
				this.categories = data;
			}.bind(this));
		} else {
			this
				.inlineWidgets
				.categories
				.setTemplateOptions({data: this.categories});
		}
	},

	newEntry: function() {
		var _  = this;
		var entry = {
			date: _.find1('#date-add').value,
			name: _.find1('#name').value,
			cat: _.find1('#type').value,
			sum: _.find1('#sum').value
		};

		vf.modules.Api.post('/api.php?method=Entry.save', 'json', entry, function() {
			console.log(arguments);
		})
	}
});
