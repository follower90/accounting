define [
  'chaplin'
  'controllers/login-controller'
  'views/base/view'
  'models/user'
  'text!templates/login.hbs'
], (Chaplin, LoginController, View, User, template) ->
  'use strict'

  class LoginView extends View
    # Automatically render after initialize.
    autoRender: true
    tagName: 'div'
    className: 'login-page'

    events:
      'submit form': 'submit'

    submit: (event) =>
      event.preventDefault()
      @model.set('login', $('input[name="login"]').val())
      @model.set('password', $('input[name="password"]').val())


      @model.fetch
        type:
          'post'
        data:
          @model.attributes
        success: (data) =>
          if data.id
            Chaplin.mediator.user = data.attributes
            localStorage.setItem("login",data.attributes.login)
            localStorage.setItem("password",data.attributes.password)
            Chaplin.utils.redirectTo 'entries#home'

          else
            console.log 'Incorrect data'
        error: =>
          console.log 'Login error'

    # Save the template string in a prototype property.
    # This is overwritten with the compiled template function.
    # In the end you might want to used precompiled templates.
    template: template
    template = null
