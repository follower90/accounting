// Generated by CoffeeScript 1.7.1
var __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

define(['chaplin', 'controllers/base/controller', 'views/login-view', 'models/user'], function(Chaplin, Controller, LoginPageView, User) {
  'use strict';
  var LoginController;
  return LoginController = (function(_super) {
    __extends(LoginController, _super);

    function LoginController() {
      return LoginController.__super__.constructor.apply(this, arguments);
    }

    LoginController.prototype.loginpage = function() {
      if (!Chaplin.mediator.user) {
        this.model = new User();
        return this.view = new LoginPageView({
          model: this.model,
          region: 'main'
        });
      } else {
        return this.redirectTo('entries#home');
      }
    };

    LoginController.prototype.logout = function() {
      this.model = new User();
      return this.model.fetch({
        success: (function(_this) {
          return function() {
            localStorage.setItem("login", '');
            localStorage.setItem("password", '');
            Chaplin.mediator.user = null;
            return _this.redirectTo('login#loginpage');
          };
        })(this)
      });
    };

    LoginController.prototype.profile = function() {
      return console.log('profile');
    };

    return LoginController;

  })(Controller);
});
