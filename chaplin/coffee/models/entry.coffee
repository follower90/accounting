define [
  'chaplin',
  'models/base/model'
], (Chaplin, Model) ->
  'use strict'

  class Entry extends Model

    url: ->
      if @.isNew
        'http://myaccounting.tk/api/entry/action/view/id/' + @.id
      else
        'http://myaccounting.tk/api/entry/action/view/id/id/' + @.id

# initialize: (attributes, options) ->
#   super
#   console.debug 'HelloWorld#initialize'
