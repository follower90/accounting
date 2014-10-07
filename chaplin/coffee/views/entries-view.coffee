define [
  'views/base/collection-view'
  'views/entry-view'
  'text!templates/entries.hbs'
], (CollectionView, EntryView, template) ->
  'use strict'

  class EntriesView extends CollectionView
    # Automatically render after initialize.
    autoRender: true
    tagName: 'ul'
    id: 'entries-container'
    itemView: EntryView

    # Save the template string in a prototype property.
    # This is overwritten with the compiled template function.
    # In the end you might want to used precompiled templates.
    template: template
    template = null