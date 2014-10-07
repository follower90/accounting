define [
  'chaplin'
  'views/base/view'
  'models/entry'
  'text!templates/entry-new.hbs'
], (Chaplin, View, Entry, template) ->
  'use strict'

  class NewEntryView extends View
    # Automatically render after initialize.
    autoRender: true
    tagName: 'div'
    className: 'new-entry'

    events:
      'submit form': 'submit'

    submit: (event)=>
      event.preventDefault()

      @model.set('date', $('input[name="date"]').val())
      @model.set('name', $('input[name="name"]').val())
      @model.set('category_id', $('#category_id').val())
      @model.set('type', $('input[name="type"]').val())
      @model.set('sum', $('input[name="sum"]').val())

      @model.save
        success: =>
          Chaplin.utils.redirectTo 'entries#home'
        error: =>
          console.log 'error'
      Chaplin.utils.redirectTo 'entries#home'

    # Save the template string in a prototype property.
    # This is overwritten with the compiled template function.
    # In the end you might want to used precompiled templates.
    template: template
    template = null
