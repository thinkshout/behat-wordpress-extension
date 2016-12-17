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
     * @param TableNode $users
     */
    public function thereAreUsers(TableNode $users)
    {
        foreach ($users->getHash() as $user) {
            $this->createUser($user['user_login'], $user['user_email'], $user);
        }
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
