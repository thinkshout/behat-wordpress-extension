---
currentMenu: drivers
---

# Drivers

The WordPress Extension for Behat provides multiple drivers for interacting with the WordPress site you are testing.
The default driver uses [WP-CLI](http://wp-cli.org/).

Feature                                  | WP-CLI                     | Browser<sup>[1](#NYI)</sup>
---------------------------------------- | -------------------------- | ---------------------------
Activate/deactive plugins.               | Yes                        | ~
Switch theme.                            | Yes                        | ~
Run tests and site on different servers. | Yes<sup>[2](#WP-CLI)</sup> | No

1. <a id="NYI"></a> Feature planned, not yet implemented.
1. WP-CLI <a href="https://wp-cli.org/blog/version-0.24.0.html#but-wait-whats-the-ssh-in-there" id="WP-CLI">supports SSH connections</a> to remote WordPress sites.
