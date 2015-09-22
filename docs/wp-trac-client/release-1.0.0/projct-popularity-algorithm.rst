Project Popularity Algorithm
----------------------------

the algorithm to decide which project is should show at the first.
Here are some facts:

- total number of tickets.
- total number of git commits
- total number of contributors
- total number of wiki page
- last modified date for tickets.
- last modified date for git commits

Process to caculate popularity
------------------------------

- we should have a column in project table to save the popularity.
- popularity for all projects will be caculated regularly by 
  a separate process... (every 20 mins, it should be configurable).

