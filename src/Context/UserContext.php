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
     * Add specified user accounts.
     *
     * Example: Given there are users:
     *     | user_login | user_pass | user_email         | role          |
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

//I am logged in as "walter" with password "test"
