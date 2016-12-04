---
currentMenu: contexts
---

# Contexts

With Behat, it is possible to flexibly structure your code by using multiple [contexts](http://docs.behat.org/en/latest/user_guide/context.html) in a single test suite. WordHat provides the following contexts:

### RawWordpressContext

This is a context that provides no step definitions, but all of the necessary functionality for interacting with WordPress and the browser.

### WordpressContext

Provides step definitions for creating posts, comments, and terms; managing plugins, themes, and the cache; the database, and much more.

### MessageContext

Provides step definitions that are specific to WordPress admin notices that get displayed in the dashboard (error and updated).
