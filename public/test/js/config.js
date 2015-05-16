vf.modules.Router.routes({
	'#/': {page: 'Layout', params: {page: 'Main'}},
	'#/profile': {page: 'Layout',  params: {page: 'Profile'}}
});

vf.options.templates = 'public/test/js/templates/';
