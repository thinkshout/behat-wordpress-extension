# Installation

1. Create a folder for your tests:

  ```Shell
  mkdir projectfolder
  cd projectfolder
  ```

  All the commands that follow are written to install from the root of your project folder.

2. Install [Composer](https://getcomposer.org/):

  ```Shell
  curl -s https://getcomposer.org/installer | php
  ```

3. Install [WP-CLI](http://wp-cli.org/) globally:

  ```Shell
  curl -o wp https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
  chmod +x wp
  ```

  Note: this executable *must* be named `wp` and be within your system's [$PATH](https://en.wikipedia.org/wiki/PATH_(variable)). For example:

  ```Shell
  sudo mv wp /usr/local/bin/
  ```

4. Create a configuration file to tell Composer what to install. To do that, paste the following code into your editor and save as `composer.json`:

  ```YAML
  {
      "require": {
        "paulgibbs/behat-wordpress-extension": "*"
      }
  }
  ```

5. Run the following command to install the WordPress Behat Extension:

  ```Shell
  php composer.phar install
  ```

6. Configure your testing environment by creating a file called behat.yml with the following. Be sure that you point the base_url at the web site YOU intend to test. Do not include a trailing slash:

  .. literalinclude: _static/snippets/behat-1.yml
     :language: yaml
     :linenos:

7. Initialize behat. This creates the features folder with some basic things to get you started, including your own FeatureContext.php file:

    bin/behat --init

8. This will generate a FeatureContext.php file that looks like:

  .. literalinclude: _static/snippets/FeatureContext.php.inc
     :language: php
     :linenos:
     :emphasize-lines: 12

  This FeatureContext.php will be aware of both the Drupal Extension and the Mink Extension, so you'll be able to take advantage of their drivers add your own custom step definitions as well.

9. To ensure everything is set up appropriately, type:

    bin/behat -dl

   You'll see a list of steps like the following, but longer, if you've installed everything successfully:

  .. code-block: gherkin
     :linenos:

      default | Given I am an anonymous user
      default | Given I am not logged in
      default | Given I am logged in as a user with the :role role(s)
      default | Given I am logged in as :name
