vf.widget('NewEntry', {

	container: '#new_entry',
	template: 'main/new',
	categories: false,
	widgets: {
		categories: {
			container: '.select-categories',
			dom: '<select class="form-control" name="types">{{options}}</select>',
			beforeRender: function() {
				var options = '';
				if (!!this.templateOptions) {
					for (var i in this.templateOptions.data) {
						var id = this.templateOptions.data[i].id,
							name = this.templateOptions.data[i].name;

						options += '<option value="' + id + '">' + name + '</option>';
					}
					this.setTemplateOptions({options: options});
				}
			}
		}
	},

	categoriesApiURL: '/api/Category.list',

	beforeRender: function () {

		if (!this.categories) {
			this
				.inlineWidgets
				.categories
				.setTemplateOptions({data: [{id: 0, name: 'Loading...'}]});

			vf.modules.Api.get(this.categoriesApiURL, 'json', function (data) {
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
	}
});
