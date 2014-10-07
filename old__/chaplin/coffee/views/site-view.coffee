define [
  'views/base/view'
  'text!templates/site.hbs'
], (View, template) ->
  'use strict'

  class SiteView extends View
    container: '#wrapper'
    id: 'site-container'
    regions:
      main: '#main-container'
    template: template
    template = null
