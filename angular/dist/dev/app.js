var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") return Reflect.decorate(decorators, target, key, desc);
    switch (arguments.length) {
        case 2: return decorators.reduceRight(function(o, d) { return (d && d(o)) || o; }, target);
        case 3: return decorators.reduceRight(function(o, d) { return (d && d(target, key)), void 0; }, void 0);
        case 4: return decorators.reduceRight(function(o, d) { return (d && d(target, key, o)) || o; }, desc);
    }
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};
var angular2_1 = require('angular2/angular2');
var router_1 = require('angular2/router');
var home_1 = require('./components/home/home');
var about_1 = require('./components/about/about');
var profile_1 = require('./components/profile/profile');
var NameList_1 = require('./services/NameList');
var CategoriesList_1 = require('./services/CategoriesList');
var App = (function () {
    function App() {
    }
    App = __decorate([
        angular2_1.Component({
            selector: 'app',
            viewBindings: [NameList_1.NamesList, CategoriesList_1.CategoriesList]
        }),
        router_1.RouteConfig([
            { path: '/', component: home_1.Home, as: 'home' },
            { path: '/about', component: about_1.About, as: 'about' },
            { path: '/profile', component: profile_1.Profile, as: 'profile' }
        ]),
        angular2_1.View({
            templateUrl: './app.html',
            directives: [router_1.RouterOutlet, router_1.RouterLink]
        }), 
        __metadata('design:paramtypes', [])
    ], App);
    return App;
})();
angular2_1.bootstrap(App, [router_1.routerInjectables]);

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImFwcC50cyJdLCJuYW1lcyI6WyJBcHAiLCJBcHAuY29uc3RydWN0b3IiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7O0FBQUEseUJBQXlDLG1CQUFtQixDQUFDLENBQUE7QUFDN0QsdUJBQXVFLGlCQUFpQixDQUFDLENBQUE7QUFFekYscUJBQW1CLHdCQUF3QixDQUFDLENBQUE7QUFDNUMsc0JBQW9CLDBCQUEwQixDQUFDLENBQUE7QUFDL0Msd0JBQXNCLDhCQUE4QixDQUFDLENBQUE7QUFFckQseUJBQXdCLHFCQUFxQixDQUFDLENBQUE7QUFDOUMsK0JBQTZCLDJCQUEyQixDQUFDLENBQUE7QUFHekQ7SUFBQUE7SUFjQUMsQ0FBQ0E7SUFkREQ7UUFBQ0Esb0JBQVNBLENBQUNBO1lBQ1BBLFFBQVFBLEVBQUVBLEtBQUtBO1lBQ2ZBLFlBQVlBLEVBQUVBLENBQUNBLG9CQUFTQSxFQUFFQSwrQkFBY0EsQ0FBQ0E7U0FDNUNBLENBQUNBO1FBQ0RBLG9CQUFXQSxDQUFDQTtZQUNUQSxFQUFDQSxJQUFJQSxFQUFFQSxHQUFHQSxFQUFFQSxTQUFTQSxFQUFFQSxXQUFJQSxFQUFFQSxFQUFFQSxFQUFFQSxNQUFNQSxFQUFDQTtZQUN4Q0EsRUFBQ0EsSUFBSUEsRUFBRUEsUUFBUUEsRUFBRUEsU0FBU0EsRUFBRUEsYUFBS0EsRUFBRUEsRUFBRUEsRUFBRUEsT0FBT0EsRUFBQ0E7WUFDL0NBLEVBQUNBLElBQUlBLEVBQUVBLFVBQVVBLEVBQUVBLFNBQVNBLEVBQUVBLGlCQUFPQSxFQUFFQSxFQUFFQSxFQUFFQSxTQUFTQSxFQUFDQTtTQUN4REEsQ0FBQ0E7UUFDREEsZUFBSUEsQ0FBQ0E7WUFDRkEsV0FBV0EsRUFBRUEsWUFBWUE7WUFDekJBLFVBQVVBLEVBQUVBLENBQUNBLHFCQUFZQSxFQUFFQSxtQkFBVUEsQ0FBQ0E7U0FDekNBLENBQUNBOztZQUVEQTtJQUFEQSxVQUFDQTtBQUFEQSxDQWRBLEFBY0NBLElBQUE7QUFHRCxvQkFBUyxDQUFDLEdBQUcsRUFBRSxDQUFDLDBCQUFpQixDQUFDLENBQUMsQ0FBQyIsImZpbGUiOiJhcHAuanMiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQge0NvbXBvbmVudCwgVmlldywgYm9vdHN0cmFwfSBmcm9tICdhbmd1bGFyMi9hbmd1bGFyMic7XG5pbXBvcnQge1JvdXRlQ29uZmlnLCBSb3V0ZXJPdXRsZXQsIFJvdXRlckxpbmssIHJvdXRlckluamVjdGFibGVzfSBmcm9tICdhbmd1bGFyMi9yb3V0ZXInO1xuXG5pbXBvcnQge0hvbWV9IGZyb20gJy4vY29tcG9uZW50cy9ob21lL2hvbWUnO1xuaW1wb3J0IHtBYm91dH0gZnJvbSAnLi9jb21wb25lbnRzL2Fib3V0L2Fib3V0JztcbmltcG9ydCB7UHJvZmlsZX0gZnJvbSAnLi9jb21wb25lbnRzL3Byb2ZpbGUvcHJvZmlsZSc7XG5cbmltcG9ydCB7TmFtZXNMaXN0fSBmcm9tICcuL3NlcnZpY2VzL05hbWVMaXN0JztcbmltcG9ydCB7Q2F0ZWdvcmllc0xpc3R9IGZyb20gJy4vc2VydmljZXMvQ2F0ZWdvcmllc0xpc3QnO1xuXG5cbkBDb21wb25lbnQoe1xuICAgIHNlbGVjdG9yOiAnYXBwJyxcbiAgICB2aWV3QmluZGluZ3M6IFtOYW1lc0xpc3QsIENhdGVnb3JpZXNMaXN0XVxufSlcbkBSb3V0ZUNvbmZpZyhbXG4gICAge3BhdGg6ICcvJywgY29tcG9uZW50OiBIb21lLCBhczogJ2hvbWUnfSxcbiAgICB7cGF0aDogJy9hYm91dCcsIGNvbXBvbmVudDogQWJvdXQsIGFzOiAnYWJvdXQnfSxcbiAgICB7cGF0aDogJy9wcm9maWxlJywgY29tcG9uZW50OiBQcm9maWxlLCBhczogJ3Byb2ZpbGUnfVxuXSlcbkBWaWV3KHtcbiAgICB0ZW1wbGF0ZVVybDogJy4vYXBwLmh0bWwnLFxuICAgIGRpcmVjdGl2ZXM6IFtSb3V0ZXJPdXRsZXQsIFJvdXRlckxpbmtdXG59KVxuY2xhc3MgQXBwIHtcbn1cblxuXG5ib290c3RyYXAoQXBwLCBbcm91dGVySW5qZWN0YWJsZXNdKTtcbiJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==