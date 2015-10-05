import {Component, View, bootstrap} from 'angular2/angular2';
import {RouteConfig, RouterOutlet, RouterLink, routerInjectables} from 'angular2/router';

import {Home} from './components/home/home';
import {About} from './components/about/about';
import {NamesList} from './services/NameList';


@Component({
    selector: 'app',
    viewBindings: [NamesList]
})
@RouteConfig([
    {path: '/', component: Home, as: 'home'},
    {path: '/about', component: About, as: 'about'}
])
@View({
    templateUrl: './app.html',
    directives: [RouterOutlet, RouterLink]
})
class App {
}


bootstrap(App, [routerInjectables]);
