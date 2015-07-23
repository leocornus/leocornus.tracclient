`wp-trac-client Release 1.0.0 <README.rst>`_
> Introduce Request Context

Introduce **wptc_request_context** so we could

- reuse source code the most,
- simplify the source code for page template

the request context will track the following information and
metadata:

**user metadata**

- tracuser: wordpress user_login

**project metadata**

- project: project name
- version: version name
- milestone: milestone

**pagination informaion**

- per_page
- page_number
