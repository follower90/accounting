define [
  'chaplin'
  'controllers/base/controller'
  'views/login-view'
  'models/user',
], (Chaplin, Controller, LoginPageView, User) ->
  'use strict'

  class LoginController extends Controller

    loginpage: ->

      if not Chaplin.mediator.user
        @model = new User()
        @view = new LoginPageView model: @model, region: 'main'
      else @redirectTo 'entries#home'


    logout: ->
      @model = new User()
      @model.fetch
        success: =>
          localStorage.setItem("login", '')
          localStorage.setItem("password", '')
          Chaplin.mediator.user = null
          @redirectTo 'login#loginpage'

    profile: ->
      console.log 'profile'