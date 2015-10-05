"format register";System.register("components/home/home",["angular2/angular2","angular2/router"],!0,function(require,e,t){var n=System.global,r=n.define;n.define=void 0;var o=this&&this.__decorate||function(e,t,n,r){if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)return Reflect.decorate(e,t,n,r);switch(arguments.length){case 2:return e.reduceRight(function(e,t){return t&&t(e)||e},t);case 3:return e.reduceRight(function(e,r){return void(r&&r(t,n))},void 0);case 4:return e.reduceRight(function(e,r){return r&&r(t,n,e)||e},r)}},a=this&&this.__metadata||function(e,t){return"object"==typeof Reflect&&"function"==typeof Reflect.metadata?Reflect.metadata(e,t):void 0},i=require("angular2/angular2"),u=require("angular2/router"),c=function(){function e(){}return e=o([i.Component({selector:"component-1"}),i.View({templateUrl:"./components/home/home.html?v=0.0.0",directives:[u.RouterLink]}),a("design:paramtypes",[])],e)}();return e.Home=c,n.define=r,t.exports}),System.register("services/NameList",[],!0,function(require,e,t){var n=System.global,r=n.define;n.define=void 0;var o=function(){function e(){this.names=["Dijkstra","Knuth","Turing","Hopper"]}return e.prototype.get=function(){return this.names},e.prototype.add=function(e){this.names.push(e)},e}();return e.NamesList=o,n.define=r,t.exports}),System.register("components/about/about",["angular2/angular2","services/NameList"],!0,function(require,e,t){var n=System.global,r=n.define;n.define=void 0;var o=this&&this.__decorate||function(e,t,n,r){if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)return Reflect.decorate(e,t,n,r);switch(arguments.length){case 2:return e.reduceRight(function(e,t){return t&&t(e)||e},t);case 3:return e.reduceRight(function(e,r){return void(r&&r(t,n))},void 0);case 4:return e.reduceRight(function(e,r){return r&&r(t,n,e)||e},r)}},a=this&&this.__metadata||function(e,t){return"object"==typeof Reflect&&"function"==typeof Reflect.metadata?Reflect.metadata(e,t):void 0},i=require("angular2/angular2"),u=require("services/NameList"),c=function(){function e(e){this.list=e}return e.prototype.addName=function(e){this.list.add(e.value),e.value=""},e=o([i.Component({selector:"component-2"}),i.View({templateUrl:"./components/about/about.html?v=0.0.0",directives:[i.NgFor]}),a("design:paramtypes",[u.NamesList])],e)}();return e.About=c,n.define=r,t.exports}),System.register("app",["angular2/angular2","angular2/router","components/home/home","components/about/about","services/NameList"],!0,function(require,e,t){var n=System.global,r=n.define;n.define=void 0;var o=this&&this.__decorate||function(e,t,n,r){if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)return Reflect.decorate(e,t,n,r);switch(arguments.length){case 2:return e.reduceRight(function(e,t){return t&&t(e)||e},t);case 3:return e.reduceRight(function(e,r){return void(r&&r(t,n))},void 0);case 4:return e.reduceRight(function(e,r){return r&&r(t,n,e)||e},r)}},a=this&&this.__metadata||function(e,t){return"object"==typeof Reflect&&"function"==typeof Reflect.metadata?Reflect.metadata(e,t):void 0},i=require("angular2/angular2"),u=require("angular2/router"),c=require("components/home/home"),s=require("components/about/about"),f=require("services/NameList"),m=function(){function e(){}return e=o([i.Component({selector:"app",viewBindings:[f.NamesList]}),u.RouteConfig([{path:"/",component:c.Home,as:"home"},{path:"/about",component:s.About,as:"about"}]),i.View({templateUrl:"./app.html",directives:[u.RouterOutlet,u.RouterLink]}),a("design:paramtypes",[])],e)}();return i.bootstrap(m,[u.routerInjectables]),n.define=r,t.exports});