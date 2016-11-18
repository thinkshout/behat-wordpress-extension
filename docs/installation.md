---
currentMenu: installation
---

# Installation

> Do you know how to use Composer? tl;dr?
>
> Require `paulgibbs/behat-wordpress-extension` and copy its `behat.yml.dist` into your project (minus `.dist`), install <a href="https://wp-cli.org/">WP-CLI</a> globally, and run `vendor/bin/behat --init`.

1. Check that all [requirements](requirements.html) are met.

1. Create a folder for your tests:

  ```Shell
  mkdir projectfolder
  cd projectfolder
  ```

  All the commands that follow are written to install from the root of your project folder.

1. Tell [Composer](https://getcomposer.org/) to install the WordPress Extension for Behat. To do this conveniently, run:

  ```Shell
  php composer.phar require paulgibbs/behat-wordpress-extension="^0.1"
  ```

  This will create a `composer.json` file you, and download the WordPress Extension for Behat, with all its dependencies.

1. The WordPress Extension for Behat comes with a sample configuration file to help you set up the test environment. Copy it into your project folder and name it `behat.yml`:

  ```Shell
  cp vendor/paulgibbs/behat-wordpress-extension/behat.yml.dist behat.yml
  ```

  Edit that file and change the `base_url` setting to point at the website that you intend to test.

1. Initialise [Behat](http://behat.org):

    ```Shell
    vendor/bin/behat --init
    ```

  This will generate a `features/` folder for your [Behat features](http://docs.behat.org/en/latest/user_guide/features_scenarios.html#features), and a new [Behat Context](http://docs.behat.org/en/latest/user_guide/context.html) in `features/bootstrap/`. The latter is aware of both the WordPress and [Mink](https://github.com/Behat/MinkExtension) extensions, so you'll be able to take advantage of their drivers as you build your own custom [step definitions or hooks](http://docs.behat.org/en/latest/user_guide/writing_scenarios.html).

1. To confirm that everything is set up correctly, run:

  ```Shell
  vendor/bin/behat -dl
  ```

  If everything worked, you will see a list of steps like the following (but much longer):

  ```Gherkin
  Given I am an anonymous user
  Given I am not logged in
  Given I am logged in as a user with the :role role(s)
  Given I am logged in as :name
  ```
