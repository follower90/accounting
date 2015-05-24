vf.require(['App.Main', 'App.Profile', 'App.Layout'],
	function(Main, Profile, Layout) {

	App.Router = vf.Router.extend('App.Router', {
		routes: {
			'#/': {page: Layout, params: {page: Main}},
			'#/profile': {page: Layout, params: {page: Profile}},
			'#/logout': {page: Layout, params: {action: 'logout'}}
		}
	});
});
