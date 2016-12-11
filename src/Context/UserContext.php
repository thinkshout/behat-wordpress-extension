<?php
namespace PaulGibbs\WordpressBehatExtension\Context;

use InvalidArgumentException;
use Behat\Gherkin\Node\TableNode;
use function PaulGibbs\WordpressBehatExtension\is_wordpress_error;

/**
 * Provides step definitions for all things relating to users.
 */
class UserContext extends RawWordpressContext
{
    /**
     * Open the dashboard.
     *
     * Example: Given I am on the dashboard
     * Example: Given I am in wp-admin
     * Example: When I go to the dashboard
     * Example: When I go to wp-admin
     *
     * @Given /^(?:I am|they are) on the dashboard/
     * @Given /^(?:I am|they are) in wp-admin/
     * @When /^(?:I|they) go to the dashboard/
     * @When /^(?:I|they) go to wp-admin/
     */
    public function iAmOnDashboard()
    {
        $this->visitPath('wp-admin/');
    }

    /**
     * Add specified user accounts.
     *
     * Example: Given there are users:
     *     | user_login | user_pass | user_email        | role          |
     *     | admin      | admin     | admin@exampåle.com | administrator |
     *
     * @Given /^there are users:/
     *
     * @param TableNode $users
     */
    public function thereAreUsers(TableNode $users)
    {
        foreach ($users->getHash() as $user) {
            $result = wp_insert_user($user);

            if (is_wordpress_error($result)) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Invalid user information schema: %s',
                        $result->get_error_message()
                    )
                );
            }
        }
    }
}

//there are posts
//I am logged in as "walter" with password "test"
