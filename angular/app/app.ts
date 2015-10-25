import {Component, View, bootstrap} from 'angular2/angular2';
import {RouteConfig, RouterOutlet, RouterLink, routerInjectables} from 'angular2/router';

import {Home} from './components/home/home';
import {About} from './components/about/about';
import {Profile} from './components/profile/profile';

import {NamesList} from './services/NameList';
import {CategoriesList} from './services/CategoriesList';


@Component({
    selector: 'app',
    viewBindings: [NamesList, CategoriesList]
})
@RouteConfig([
    {path: '/', component: Home, as: 'home'},
    {path: '/about', component: About, as: 'about'},
    {path: '/profile', component: Profile, as: 'profile'}
])
@View({
    templateUrl: './app.html',
    directives: [RouterOutlet, RouterLink]
})
class App {
}


bootstrap(App, [routerInjectables]);
