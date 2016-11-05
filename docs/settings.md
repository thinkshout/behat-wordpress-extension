---
currentMenu: settings
---

# Settings

* `Behat\MinkExtension`
  * `base_url`: URL to the WordPress site.
* `PaulGibbs\WordpressExtension`
  * `path`: Absolute path to the WordPress files.

# Environment-specific settings

Some of the settings in `behat.yml` are environment specific. For example, the base URL may be `development.dev` on your local development environment, while on a test server it might be `test.dev`. Other environment specific settings include the WordPress root path.

If you intend to run your tests on different environments these settings should not be committed to `behat.yml`. Instead they should be exported in an environment variable. Before running tests, Behat will check the `BEHAT_PARAMS` environment variable and add these settings to the ones that are present in `behat.yml`. This variable should contain a JSON object with your settings.

Example JSON object:

```JSON
{
  "extensions": {
    "Behat\\MinkExtension": {
      "base_url": "http://development.dev"
    },
    "PaulGibbs\\WordpressExtension": {
      "path": "/srv/www/development.dev"
    }
  }
}
```

To export this into the ``BEHAT_PARAMS`` environment variable, squash the JSON object into a single line and surround with single quotes:

```Shell
export BEHAT_PARAMS='{"extensions":{"Behat\\MinkExtension":{"base_url":"http://development.dev"},"PaulGibbs\\WordpressExtension":{"path":"/www/development.dev"}}}'
```
