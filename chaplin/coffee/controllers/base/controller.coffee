define ['chaplin', 'models/user','views/site-view'], (Chaplin, User, SiteView) ->
  'use strict'

  class Controller extends Chaplin.Controller
    # Place your application-specific controller features here.
    beforeAction: (params, route) ->
      @reuse 'site', SiteView

      if not Chaplin.mediator.user
        unless route.path is  'login' and route.action is 'loginpage'
          @model = new User()

          @login = localStorage.getItem('login')
          @password = localStorage.getItem('password')

          if (@login != '' and @password != '') or @login or @password
            @model.fetch
              type:
                'post'
              data:
                'login': @login
                'password': @password
              success: (data) =>
                Chaplin.mediator.user = data.attributes
                Chaplin.utils.redirectTo('entries#home')
              error: =>
                @redirectTo 'login#loginpage'
          else
            @redirectTo 'login#loginpage'