---
currentMenu: requirements
---

# System Requirements

* A [WordPress](https://wordpress.org/) site, running version 4.7+.
* [WP-CLI](http://wp-cli.org/) installed globally:
  ```Shell
  curl -o wp https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
  chmod +x wp
  ```
  The executable *must* be named `wp` and be within your system's [$PATH](https://en.wikipedia.org/wiki/PATH_(variable)).

* PHP 5.4+, with the [cURL extension](https://curl.haxx.se/libcurl/php/install.html) enabled.
* [Composer](https://getcomposer.org/).
* [Selenium](http://docs.seleniumhq.org/download/).
  * Download the Standalone Server, place the file wherever you want, and run with the following command:
```Shell
java -jar selenium-server-standalone-3.0.1.jar
```
