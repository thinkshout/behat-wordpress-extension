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
     * @Given /^(?:there are|there is a) users?:/
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
     * Add user account, and go to their author archive page.
     *
     * Example: Given I am viewing an author archive:
     *     | user_login | user_pass | user_email        | role          |
     *     | admin      | admin     | admin@example.com | administrator |
     *
     * @Given /^(?:I am|they are) viewing an author archive:/
     *
     * @param TableNode $user_data
     */
    public function iAmViewingAuthorArchive(TableNode $user_data)
    {
        $params = $this->getWordpressParameters();

        // Create user.
        $data     = $user_data->getHash();
        $new_user = $this->createUser($data['user_login'], $data['user_email'], $data);

        // Store new users by username, not by role (unlike what the docs say).
        $id = strtolower($data['user_login']);
        $params['users'][$id] = array(
            'username' => $data['user_login'],
            'password' => $data['user_pass'],
        );

        $this->setWordpressParameters($params);

        // Navigate to archive.
        $this->visitPath( sprintf('author/%s/', $new_user['slug'] ) );
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
