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
var NameList_1 = require('../../services/NameList');
var About = (function () {
    function About(list) {
        this.list = list;
    }
    About.prototype.addName = function (newname) {
        this.list.add(newname.value);
        newname.value = '';
    };
    About = __decorate([
        angular2_1.Component({
            selector: 'component-2'
        }),
        angular2_1.View({
            templateUrl: './components/about/about.html?v=0.0.0',
            directives: [angular2_1.NgFor]
        }), 
        __metadata('design:paramtypes', [NameList_1.NamesList])
    ], About);
    return About;
})();
exports.About = About;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImNvbXBvbmVudHMvYWJvdXQvYWJvdXQudHMiXSwibmFtZXMiOlsiQWJvdXQiLCJBYm91dC5jb25zdHJ1Y3RvciIsIkFib3V0LmFkZE5hbWUiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7O0FBQUEseUJBQXFDLG1CQUFtQixDQUFDLENBQUE7QUFFekQseUJBQXdCLHlCQUF5QixDQUFDLENBQUE7QUFFbEQ7SUFRSUEsZUFBbUJBLElBQWVBO1FBQWZDLFNBQUlBLEdBQUpBLElBQUlBLENBQVdBO0lBQ2xDQSxDQUFDQTtJQUNERCx1QkFBT0EsR0FBUEEsVUFBUUEsT0FBT0E7UUFDWEUsSUFBSUEsQ0FBQ0EsSUFBSUEsQ0FBQ0EsR0FBR0EsQ0FBQ0EsT0FBT0EsQ0FBQ0EsS0FBS0EsQ0FBQ0EsQ0FBQ0E7UUFDN0JBLE9BQU9BLENBQUNBLEtBQUtBLEdBQUdBLEVBQUVBLENBQUNBO0lBQ3ZCQSxDQUFDQTtJQWJMRjtRQUFDQSxvQkFBU0EsQ0FBQ0E7WUFDUEEsUUFBUUEsRUFBRUEsYUFBYUE7U0FDMUJBLENBQUNBO1FBQ0RBLGVBQUlBLENBQUNBO1lBQ0ZBLFdBQVdBLEVBQUVBLGdEQUFnREE7WUFDN0RBLFVBQVVBLEVBQUVBLENBQUNBLGdCQUFLQSxDQUFDQTtTQUN0QkEsQ0FBQ0E7O2NBUURBO0lBQURBLFlBQUNBO0FBQURBLENBZEEsQUFjQ0EsSUFBQTtBQVBZLGFBQUssUUFPakIsQ0FBQSIsImZpbGUiOiJjb21wb25lbnRzL2Fib3V0L2Fib3V0LmpzIiwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IHtDb21wb25lbnQsIFZpZXcsIE5nRm9yfSBmcm9tICdhbmd1bGFyMi9hbmd1bGFyMic7XG5cbmltcG9ydCB7TmFtZXNMaXN0fSBmcm9tICcuLi8uLi9zZXJ2aWNlcy9OYW1lTGlzdCc7XG5cbkBDb21wb25lbnQoe1xuICAgIHNlbGVjdG9yOiAnY29tcG9uZW50LTInXG59KVxuQFZpZXcoe1xuICAgIHRlbXBsYXRlVXJsOiAnLi9jb21wb25lbnRzL2Fib3V0L2Fib3V0Lmh0bWw/dj08JT0gVkVSU0lPTiAlPicsXG4gICAgZGlyZWN0aXZlczogW05nRm9yXVxufSlcbmV4cG9ydCBjbGFzcyBBYm91dCB7XG4gICAgY29uc3RydWN0b3IocHVibGljIGxpc3Q6IE5hbWVzTGlzdCkge1xuICAgIH1cbiAgICBhZGROYW1lKG5ld25hbWUpIHtcbiAgICAgICAgdGhpcy5saXN0LmFkZChuZXduYW1lLnZhbHVlKTtcbiAgICAgICAgbmV3bmFtZS52YWx1ZSA9ICcnO1xuICAgIH1cbn1cbiJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==