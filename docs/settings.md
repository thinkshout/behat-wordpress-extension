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
```

Option             | Default value | Description
-------------------| ------------- | -----------
`default_driver`   | **wpcli**     | The [driver](drivers.html) to use (either "wpcli" or "browser").
`path`             | _null_        | _Optional_. Path to WordPress files.
`wpcli.alias`      | _null_        | _Optional_. [WP-CLI alias](https://wp-cli.org/commands/cli/alias/) (preferred over `wpcli.path`).
`wpcli.users`      | _see example_ | Keys must match WordPress roles.
`users.*.username` |               | The name of a user with the specified role.
`users.*.password` |               | The password of a user with the specified role.


## Behat\MinkExtension

Extension `Behat\MinkExtension` integrates Mink into Behat. [Visit its website](http://mink.behat.org/en/latest/) for more information.
