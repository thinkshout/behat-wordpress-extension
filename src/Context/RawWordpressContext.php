<?php
namespace PaulGibbs\WordpressBehatExtension\Context;

use RuntimeException;

use Behat\Behat\Context\SnippetAcceptingContext,
    Behat\Mink\Exception\ExpectationException,
    Behat\MinkExtension\Context\RawMinkContext;

use PaulGibbs\WordpressBehatExtension\WordpressDriverManager;

/**
 * Base Behat context.
 *
 * Does not contain any step defintions.
 */
class RawWordpressContext extends RawMinkContext implements WordpressAwareInterface, SnippetAcceptingContext
{
    /**
     * WordPress driver manager.
     *
     * @var WordpressDriverManager
     */
    protected $wordpress;

    /**
     * WordPress parameters.
     *
     * @var array
     */
    protected $wordpress_parameters;

    /**
     * Is a user currently authenticated?
     *
     * @var bool
     */
    protected $user_authenticated = false;


    /**
     * Constructor.
     */
    public function __construct() {
    }

    /**
     * Set WordPress instance.
     *
     * @param WordpressDriverManager $wordpress
     */
    public function setWordpress(WordpressDriverManager $wordpress)
    {
        $this->wordpress = $wordpress;
    }

    /**
     * Get WordPress instance.
     *
     * @return WordpressDriverManager
     */
    public function getWordpress()
    {
        return $this->wordpress;
    }

    /**
     * Set parameters provided for WordPress.
     *
     * @param array $parameters
     */
    public function setWordpressParameters($parameters)
    {
        $this->wordpress_parameters = $parameters;
    }

    /**
     * Get a specific WordPress parameter.
     *
     * @param string $name Parameter name.
     * @return mixed
    */
    public function getWordpressParameter($name)
    {
        return isset($this->wordpress_parameters[$name]) ? $this->wordpress_parameters[$name] : null;
    }

    /**
     * Get active WordPress Driver.
     *
     * @param string $name Optional. Name of specific driver to retrieve.
     * @return PaulGibbs\WordpressBehatExtension\Driver\WordpressDriver
     */
    public function getDriver($name = '')
    {
        return $this->getWordpress()->getDriver($name);
    }

    /**
     * Wrap a closure in a spin check.
     *
     * This is a technique to accommodate in-progress state changes in a web page (i.e. waiting for new data to load)
     * by retrying the action for a given number of attempts, each delayed by 1 second. The closure is expected to
     * throw an exception should the expected state not (yet) exist.
     *
     * @param callable $closure Action to execute.
     * @param int      $tries  Optional. Number of attempts to make before giving up.
     */
    public function spins(callable $closure, $tries = 10)
    {
        for ($i = 0; $i <= $tries; $i++) {
            try {
                call_user_func($closure);
                return;
            } catch (\Exception $e) {
                if ($i === $tries) {
                    throw $e;
                }
            }

            sleep(1);
        }
    }

    /**
     * Log in the user.
     *
     * @param string $username
     * @param string $password
     */
    public function logIn($username, $password) {
        if ($this->user_authenticated) {
            return;
        }

        $this->visitPath('wp-login.php?redirect_to=' . urlencode($this->locatePath('/')));

        $page = $this->getSession()->getPage();
        $page->fillField('user_login', $username);
        $page->fillField('user_pass', $password);
        $page->findButton('wp-submit')->click();

        $this->spins(function() use ($page) {
            if (! $page->has('css', 'body.logged-in')) {
                throw new ExpectationException('The user is not logged-in.', $this->getSession()->getDriver());
            }
        });
    }

    /**
     * Log the current user out.
     */
    public function logOut() {
        if (! $this->user_authenticated) {
            return;
        }

        $this->visitPath('wp-login.php?action=logout');
        $this->user_authenticated = false;
    }

    /**
     * Determine if the current user is logged in or not.
     *
     * @return bool
     */
    public function isUserAuthenticated() {
        return (bool) $this->user_authenticated;
    }

    /**
     * Clear object cache.
     *
     * @AfterScenario
     */
    public function clearCache() {
        $this->getDriver()->clearCache();
    }

    /**
     * Activate a plugin.
     *
     * @param string $plugin
     */
    public function activatePlugin($plugin)
    {
        $this->getDriver()->activatePlugin($plugin);
    }

    /**
     * Deactivate a plugin.
     *
     * @param string $plugin
     */
    public function deactivatePlugin($plugin)
    {
        $this->getDriver()->deactivatePlugin($plugin);
    }

    /**
     * Switch active theme.
     *
     * @param string $theme
     */
    public function switchTheme($theme)
    {
        $this->getDriver()->switchTheme($theme);
    }

    /**
     * Create a term in a taxonomy.
     *
     * @param string $term
     * @param string $taxonomy
     * @param array  $args     Optional. Set the values of the new term.
     * @return int Term ID.
     */
    public function createTerm($term, $taxonomy, $args = [])
    {
        return $this->getDriver()->createTerm($term, $taxonomy, $args);
    }

    /**
     * Delete a term from a taxonomy.
     *
     * @param int    $term_id
     * @param string $taxonomy
     */
    public function deleteTerm($term_id, $taxonomy)
    {
        $this->getDriver()->activatePlugin($term_id, $taxonomy);
    }

    /**
     * Create content.
     *
     * @param array $args Set the values of the new content item.
     * @return int Content ID.
     */
    public function createContent($args)
    {
        return $this->getDriver()->createContent($args);
    }

    /**
     * Delete specified content.
     *
     * @param int   $id   ID of content to delete.
     * @param array $args Optional. Extra parameters to pass to WordPress.
     */
    public function deleteContent($id, $args = [])
    {
        $this->getDriver()->deleteContent($id, $args);
    }

    /**
     * Create a comment.
     *
     * @param array $args Set the values of the new comment.
     * @return int Content ID.
     */
    public function createComment($args)
    {
        return $this->getDriver()->createComment($args);
    }

    /**
     * Delete specified comment.
     *
     * @param int   $id   ID of comment to delete.
     * @param array $args Optional. Extra parameters to pass to WordPress.
     */
    public function deleteComment($id, $args = [])
    {
        $this->getDriver()->deleteComment($id, $args);
    }

    /**
     * Export WordPress database.
     *
     * @return string Absolute path to database SQL file.
     */
    public function exportDatabase()
    {
        return $this->getDriver()->exportDatabase();
    }

    /**
     * Import WordPress database.
     *
     * @param string $import_file Relative path and filename of SQL file to import.
     */
    public function importDatabase($import_file)
    {
        $this->getDriver()->importDatabase($import_file);
    }
}
