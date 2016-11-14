---
currentMenu: settings
---

# Settings

## PaulGibbs\WordpressBehatExtension

Extension `PaulGibbs\WordpressBehatExtension` integrates [WordPress](https://wordpress.org/) into Behat. These are its configuration options:

```YAML
PaulGibbs\WordpressBehatExtension:
  default_driver: wpcli
  wpcli:
    alias: dev
    path: /www/example.com
```

Option              | Default value      | Description
------------------- | ------------------ | -----------
**default_driver**  | wpcli              | The driver to use (either "wpcli" or "browser").
**wpcli.alias**     | _not set_          | Optional. [WP-CLI alias](https://wp-cli.org/commands/cli/alias/) (preferred over `wpcli.path`).
**wpcli.path**      | _not set_          | Optional. Path to WordPress files.


## Behat\MinkExtension

Extension `Behat\MinkExtension` integrates [Mink](http://mink.behat.org/en/latest/) into Behat. [Visit its website](https://github.com/Behat/MinkExtension/blob/master/doc/index.rst) for more information.
