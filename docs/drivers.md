---
currentMenu: drivers
---

# Drivers

WordHat provides a range of drivers for interacting with the WordPress site you are testing. A driver represents and manages the connection between the Behat and WordPress environments.

The default driver is `wpcli`. To specify which driver to use for your tests, set [`default_driver`](settings.html) in your `behat.yml` file.

Different drivers support different features:

Feature                                  | WP-CLI                     | WordPress API | Blackbox
---------------------------------------- | -------------------------- | ------------- | --------
Publish posts and comments.              | Yes                        | Yes           | No
Create terms for taxonomy.               | Yes                        | Yes           | No
Create users.                            | Yes                        | Yes           | No
Manage plugins.                          | Yes                        | Yes           | No
Switch theme.                            | Yes                        | Yes           | No
Clear cache.                             | Yes                        | Yes           | No
Import/export MySQL backup.              | Yes                        | No            | No
Run tests and site on different servers. | Yes<sup>[1](#WP-CLI)</sup> | No            | Yes

1. WP-CLI <a href="https://wp-cli.org/blog/version-0.24.0.html#but-wait-whats-the-ssh-in-there" id="WP-CLI">supports SSH connections</a> to remote WordPress sites.
