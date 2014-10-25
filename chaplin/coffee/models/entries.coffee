define [
  'chaplin',
  'models/entry'
  'models/base/collection'
], (Chaplin, Entry, Collection) ->
  'use strict'

  class Entries extends Collection
    model: Entry
    url: 'http://myaccounting.tk/api/entry/action/list'