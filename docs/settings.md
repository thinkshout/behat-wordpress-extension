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
**default_driver**  | wpcli              | Name of the Driver to use.
**wpcli.alias**     | _not set_          | Optional. [WP-CLI alias](https://wp-cli.org/commands/cli/alias/) (preferred over `wpcli.path`).
**wpcli.path**      | `/www/example.com` | Path to WordPress files.


## Behat\MinkExtension

Extension `Behat\MinkExtension` integrates [Mink](http://mink.behat.org/en/latest/) into Behat. [Visit its website](https://github.com/Behat/MinkExtension/blob/master/doc/index.rst) for more information.
