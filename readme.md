This extension offers a simple way to begin testing and driving your WordPress applications with Behat. Some benefits include:

- **Efficient:** Like WordPress' PHPUnit implementation, SQL transactions are used to restore the database to a clean state before each scenario is tested.
- **Environments:** Supports both release and development versions of WordPress.
- **Workflow:** A number of useful traits are available, which will speed up your Behat workflow.

To get started, you only need to follow a few steps:

# 1. Install Dependencies

    composer require paulgibbs/behat-wordpress-extension

This will give us access to Behat, Mink, and, of course, the WordPress extension.

# 2. Create the Behat.yml Configuration File

Next, within your project root, create a `behat.yml` file, and add:

```
default:
    extensions:
        paulgibbs\WordPress\Behat:
            # env_path: .env.behat
```

You may pass an optional parameter, `env_path` (currently commented out above) to specify the name of the environment file that should be referenced from your tests. By default, it'll look for a `.env.behat` file.

# 3. Write Some Features

TODO

## Feature Context Traits

As a convenience, this package also includes a number of traits to streamline common tasks, such as using database transactions, or even testing mail.

### Database Transactions

Like WordPress' integration with PHPUnit, database transactions are used to clean the environment before each scenario is tested. To take advantage of this, opull in the `Laracasts\Behat\Context\DatabaseTransactions` trait, like so:

```php
// ...

use Laracasts\Behat\Context\DatabaseTransactions;

class FeatureContext extends MinkContext implements Context, SnippetAcceptingContext
{
    use DatabaseTransactions;

}
```

Once you pull in this trait, before each scenario runs, it'll begin a new transaction. And when the scenario completes, we'll roll it back for you.
