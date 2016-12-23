---
currentMenu: settings
---

# Settings

Behat uses [YAML](https://en.wikipedia.org/wiki/YAML) for its configuration file.


## PaulGibbs\WordpressBehatExtension

Extension `PaulGibbs\WordpressBehatExtension` integrates WordPress into Behat. These are its configuration options:

```YAML
PaulGibbs\WordpressBehatExtension:
  default_driver: wpcli
  path: /www/example.com
  wpcli:
    alias: dev
  users:
    admin:
      username: admin
      password: admin
    editor:
      username: editor
      password: editor
    author:
      username: author
      password: author
    contributor:
      username: contributor
      password: contributor
    subscriber:
      username: subscriber
      password: subscriber
  permalinks:
    author_archive: author/%s/
```

Option             | Default value | Description
-------------------| ------------- | -----------
`default_driver`   | _wpcli_       | The [driver](drivers.html) to use ("wpcli", "wpapi", "blackbox").
`path`             | _null_        | _Optional_. Path to WordPress files.
`wpcli.alias`      | _null_        | _Optional_. [WP-CLI alias](https://wp-cli.org/commands/cli/alias/) (preferred over `wpcli.path`).
`users.*`          | _see example_ | Keys must match names of WordPress roles.
`permalinks.*`     | _see example_ | Permalink pattern for the specified kind of link.<br>`%s` is replaced with user ID/nicename/etc, as appropriate.


## Behat\MinkExtension

Extension `Behat\MinkExtension` integrates Mink into Behat. [Visit its website](http://mink.behat.org/en/latest/) for more information.
