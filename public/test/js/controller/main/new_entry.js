app.registerComponent('New_Entry', {

	container: '#new_entry',
	template: 'main/new',
	categories: false,
	components: {
		categories: {
			container: '.select-categories',
			dom: '<select class="form-control" id="type" name="types">{{options}}</select>',
			use: 'Select_Box'
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
			this.getInlineComponent('categories')
				.setTemplateOptions({data: [{id: 0, name: 'Loading...'}]});

			app.module('Api').get('/api.php?method=Category.list', 'json', function (data) {
				this.getInlineComponent('categories')
					.setTemplateOptions({data: data.response}).load();
				this.categories = data.response;
			}.bind(this));
		} else {
			this.getInlineComponent('categories')
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

		app.module('Api').post('/api.php?method=Entry.save', 'json', entry, function() {
			app.module('Notification').send(
				'Entry added',
				'http://cdn.sstatic.net/stackexchange/img/logos/so/so-icon.png',
				'Create entry success',
				function() {
					console.log('44545');
				}
			);
		})
	}
});
