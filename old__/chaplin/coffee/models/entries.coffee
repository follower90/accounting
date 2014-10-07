define [
  'chaplin',
  'models/entry'
  'models/base/collection'
], (Chaplin, Entry, Collection) ->
  'use strict'

  class Entries extends Collection
    model: Entry
    url: 'http://myacc.bl.ee/api/entry/action/list'