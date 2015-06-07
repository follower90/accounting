app.registerRoutes({
	'#/': {page: 'Layout', params: {page: 'Main_Page'}},
	'#/profile': {page: 'Layout', params: {page: 'Profile'}},
	'#/logout': {page: 'Layout', params: {action: 'logout'}}
});
