 `wp-trac-client Release 1.0.0 <README.rst>`_
> Introduce Request Context

Overview
--------

The request context will be used to track information and data
between requests.
We will also use it to communicate between JavaScript client
and server site functions.
Request context will combine bothe HTTP request and cookie.

Context Specs
-------------

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
- total_items.

PHP function / class?
---------------------

Introduce **wptc_request_context** so we could

- reuse source code the most,
- simplify the source code for page template

We will using PHP class for the request context.
using **namespace** and class loader from **symfony**.

namespace and folder structure::

  lib
    |- Wptc
        |- RequestContext.php

JavaScript Class
----------------

We will use jquery.cookie_ to manipulate cookies.

.. _jquery.cookie: https://github.com/carhartl/jquery-cookie
