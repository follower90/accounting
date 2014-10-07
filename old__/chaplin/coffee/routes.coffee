define ->
  'use strict'

  # The routes for the application. This module returns a function.
  # `match` is match method of the Router

  (match) ->
    match '', 'entries#home'
    match 'entries', 'entries#home'
    match 'entries-:date', 'entries#month'
    match 'login', 'login#loginpage'
    match 'logout', 'login#logout'
    match 'profile', 'login#profile'
    match 'edit-:id', 'entries#edit'
    match 'delete-:id', 'entries#delete'
    match 'add', 'entries#new'