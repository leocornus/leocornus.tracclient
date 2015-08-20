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

Proposed Project Metadta
````````````````````````

a project should have the following metadata:

:name:
    unique name for a project

:description:
    brief description for a project

:owners:
    owners for a project, could be more than one owner.
    **wp_users login_name** using ',' as delimiter

:version/sprint:
    minor releases of a project
    **association table** to metadata management table.

:milestone:
    major releases for a project
    **association table** to metadata management table.

:git repository:
    zero, one or more git repositories
    **need association table** to git repo management.

:wiki categories:    
    zero, one or more wiki categories.
    **Category string** using ',' as delimiter

:blogs:

New Database Schema
-------------------

new proposed database schema be tables:

wptc_project
````````````

:ID: the unique id, primary key.
:owners: 
    owner of a project, a set of wp **user_login** using ',' as
    delimiter.
:name: unique name for a project.
:description: brief description for a project.
:repos:
    a list repositories using ',' as delimiter.
:categories:
    a set of categories using ',' as delimiter.
:blogs:
    a set of blogs using ',' as delimiter for a project

wptc_project_metadata
`````````````````````

:id: auto increment primary key
:name: metadata name
:project_id: associate with id of a project.
:type: metadata type: version/sprint, milestone
:description: description of this metadata
:due_date: due date for this metadata.
