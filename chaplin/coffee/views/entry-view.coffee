define [
  'views/base/view'
  'models/entry'
  'text!templates/entry.hbs'
], (View, Entry, template) ->
  'use strict'

  class EntryView extends View
    # Automatically render after initialize.
    autoRender: true
    tagName: 'li'
    className: 'entry'


    # Save the template string in a prototype property.
    # This is overwritten with the compiled template function.
    # In the end you might want to used precompiled templates.
    template: template
    template = null

