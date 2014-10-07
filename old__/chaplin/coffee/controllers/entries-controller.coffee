define [
  'chaplin'
  'controllers/base/controller'
  'models/entries',
  'models/entry',
  'models/types',
  'views/entry-view'
  'views/entryedit-view'
  'views/newentry-view'
  'views/entries-view'
], (Chaplin, Controller, Entries, Entry, Types, EntryView, EntryEditView, NewEntryView, EntriesView) ->
  'use strict'

  class EntriesController extends Controller
    home: ->
      @collection = new Entries()
      @collection.fetch
        type:
          'post'
        data:
          user_id: Chaplin.mediator.user.id
        success: =>
          $('#main-container').html('')
          @view = new EntriesView collection: @collection, region: 'main'
        error: =>
          $('#main-container').html('Server Error')

    month: (params) ->
      date = params.date.split('-')
      @month = date[0]
      @year = date[1]

      @collection = new Entries()
      @collection.fetch
        data:
          month: @month
          year: @year
          user_id: Chaplin.mediator.user.id
        success: =>
          $('#main-container').html('')
          @view = new EntriesView collection: @collection, region: 'main'
        error: =>
          $('#main-container').html('Server Error')

    new: ->
      @entry = new Entry()
      that = this
      @types = new Types()
      @types.fetch
        type:
          'post'
        data:
          user_id: Chaplin.mediator.user.id
        success:=>
          that.entry.attributes.types = @types.toJSON()
          @view = new NewEntryView model: that.entry, region: 'main'

    edit: (params) ->

      @entry = new Entry()
      that = this
      @entry.fetch
        type:
          'post'
        data:
          id: params.id
          user_id: Chaplin.mediator.user.id
        success: =>
          $('#main-container').html('')
          @types = new Types()
          @types.fetch
            type:
              'post'
            data:
              user_id: Chaplin.mediator.user.id
            success:=>
              that.entry.attributes.types = @types.toJSON()
              @view = new EntryEditView model: that.entry, region: 'main'
        error: =>
          $('#main-container').html('Server Error')

    delete: (params) ->
        @_id = params.id
        if confirm('Are you sure deleting id #' + @_id + '?')
          @entry = new Entry({ id: @_id })
          @entry.destroy
            dataType: 'text'
            success: =>
              console.log 'Delete success'
              $('.entry-' + @_id).remove()
            error: =>
              console.log 'Delete error occured'

        @redirectTo('entries#home')

