Rethink about Project management based on Trac lient plugin

Project Metadata
----------------

data structure and metadata for a project.

Current Database Schema
```````````````````````

current we defined 2 tables for the project management.

:WPTC_PROJECT: 
    project table

:WPTC_PROJECT_METADATA: 
    project metadata table, define and config 
    milestone, sprint (version) for a project.

:name: unique name for a project
:description: brief description for a project

association to one or more repository.


