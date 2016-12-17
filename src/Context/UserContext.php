<?php
namespace PaulGibbs\WordpressBehatExtension\Context;

use Behat\Gherkin\Node\TableNode;
use function PaulGibbs\WordpressBehatExtension\is_wordpress_error;
use RuntimeException;

/**
 * Provides step definitions for all things relating to users.
 */
class UserContext extends RawWordpressContext
{
    /**
     * Add specified user accounts.
     *
     * Example: Given there are users:
     *     | user_login | user_pass | user_email        | role          |
     *     | admin      | admin     | admin@example.com | administrator |
     *
     * @Given /^there are users:/
     *
     * @param TableNode $new_users
     */
    public function thereAreUsers(TableNode $new_users)
    {
        $params = $this->getWordpressParameters();

        foreach ($new_users->getHash() as $new) {
            $this->createUser($new['user_login'], $new['user_email'], $new);

            // Store new users by username, not by role (unlike what the docs say).
            $id = strtolower($new['user_login']);
            $params['users'][$id] = array(
                'username' => $new['user_login'],
                'password' => $new['user_pass'],
            );
        }

        $this->setWordpressParameters($params);
    }

    /**
     * Log user out.
     *
     * Example: Given I am an anonymous user
     *
     * @Given /^(?:I am|they are) an anonymous user/
     */
    public function iAmAnonymousUser()
    {
        $this->logOut();
    }

    /**
     * Log user in.
     *
     * Example: Given I am logged in as an admin
     *
     * @Given /^(?:I am|they are) logged in as (?:a|an) ([\d\w]+)$/
     *
     * @param string $role
     */
    public function iAmLoggedInAs($role)
    {
        $role  = strtolower($role);
        $users = $this->getWordpressParameter('users');

        if ($users === null || empty($users[$role])) {
            throw new RuntimeException("User details for role \"{$role}\" not found.");
        }

        $this->logIn($users[$role]['username'], $users[$role]['password']);
    }
}
