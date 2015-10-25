import {Component, View, NgFor} from 'angular2/angular2';

import {CategoriesList} from '../../services/CategoriesList';

@Component({
    selector: 'component-3'
})
@View({
    templateUrl: './components/profile/profile.html?v=<%= VERSION %>',
    directives: [NgFor]
})
export class Profile {
    constructor(public list: CategoriesList) {
    }
    addCategory(newcat) {
        this.list.add(newcat.value);
        newcat.value = '';
    }
}
