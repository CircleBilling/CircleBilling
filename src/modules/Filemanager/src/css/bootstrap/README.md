TWITTER BOOTSTRAP
=================

Bootstrap is Twitter's toolkit for kickstarting CSS for websites, apps, and more. It includes base CSS styles for typography, forms, buttons, tables, grids, navigation, alerts, and more.

To get started -- checkout http://twitter.github.com/bootstrap!


Usage
-----

You can use Twitter Bootstrap in one of two ways: just drop the compiled CSS into any new project and start cranking, or run LESS on your site and compile on the fly like a boss.

Here's what the LESS version looks like:

    <link rel="stylesheet/less" type="text/css" href="lib/bootstrap.less">
    <script src="less.js" type="text/javascript"></script>

Or if you prefer, the standard css way:

    <link rel="stylesheet" type="text/css" href="bootstrap-1.0.0.css">

For more info, refer to the docs!


Bug Tracker
-----------

Have a bug? Please create an issue here on GitHub!

https://github.com/twitter/bootstrap/issues


Mailing List
------------

Have a question? Ask on our mailing list!

twitter-bootstrap@googlegroups.com

http://groups.google.com/group/twitter-bootstrap


Developers
----------

We have included a makefile with convenience methods for working with the bootstrap library.

+ **build** - `make build`
This will run the less compiler on the bootstrap lib and generate a bootstrap.css and bootstrap.min.css file.
The lessc compiler is required for this command to run.

+ **watch** - `make watch`
This is a convenience method for watching your less files and automatically building them whenever you save.
Watchr is required for this command to run.


AUTHORS
-------

**Mark Otto**

+ http://twitter.com/mdo
+ http://github.com/markdotto

**Jacob Thornton**

+ http://twitter.com/fat
+ http://github.com/fat


Copyright and License
---------------------

Copyright 2011 Twitter, Inc.

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this work except in compliance with the License.
You may obtain a copy of the License in the LICENSE file, or at:

   http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.