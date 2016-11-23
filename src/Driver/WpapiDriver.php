<?php
namespace PaulGibbs\WordpressBehatExtension\Driver;

/**
 * Connect Behat to WordPress by loading WordPress directly into the global scope.
 */
class WpapiDriver extends BaseDriver
{
    /**
     * Path to WordPress' files.
     *
     * @var string
     */
    protected $path = '';


    /**
     * Constructor.
     *
     * @param string $path Absolute path to WordPress site's files. This or $alias must be not falsey.
     */
    public function __construct($path)
    {
        $this->path = realpath($path);
    }

    /**
     * Set up anything required for the driver.
     *
     * Called when the driver is used for the first time.
     */
    public function bootstrap()
    {
        if (! defined('ABSPATH')) {
            define('ABSPATH', "{$this->path}/");
        }

        $_SERVER['DOCUMENT_ROOT']   = $this->path;
        $_SERVER['HTTP_HOST']       = '';
        $_SERVER['REQUEST_METHOD']  = 'GET';
        $_SERVER['REQUEST_URI']     = '/';
        $_SERVER['SERVER_NAME']     = '';
        $_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';

        if (! file_exists("{$this->path}/index.php")) {
            throw new RuntimeException(sprintf('WP-CLI cannot find WordPress at %s.', $this->path));
        }

        // "Cry 'Havoc!' and let slip the dogs of war".
        require_once "{$this->path}/index.php";

        $this->is_bootstrapped = true;
    }

    /**
     * Clear object cache.
     */
    public function clearCache()
    {
    }

    /**
     * Activate a plugin.
     *
     * @param string $plugin
     */
    public function activatePlugin($plugin)
    {
    }

    /**
     * Deactivate a plugin.
     *
     * @param string $plugin
     */
    public function deactivatePlugin($plugin)
    {
    }

    /**
     * Switch active theme.
     *
     * @param string $theme
     */
    public function switchTheme($theme)
    {
    }

    /**
     * Create a term in a taxonomy.
     *
     * @param string $term
     * @param string $taxonomy
     * @param array  $args Optional. Set the values of the new term.
     * @return int Term ID.
     */
    public function createTerm($term, $taxonomy, $args = [])
    {
    }

    /**
     * Delete a term from a taxonomy.
     *
     * @param int    $term_id
     * @param string $taxonomy
     */
    public function deleteTerm($term_id, $taxonomy)
    {
    }

    /**
     * Create content.
     *
     * @param array $args Set the values of the new content item.
     * @return int Content ID.
     */
    public function createContent($args)
    {
    }

    /**
     * Delete specified content.
     *
     * @param int   $id ID of content to delete.
     * @param array $args Optional. Extra parameters to pass to WordPress.
     */
    public function deleteContent($id, $args = [])
    {
    }

    /**
     * Create a comment.
     *
     * @param array $args Set the values of the new comment.
     * @return int Comment ID.
     */
    public function createComment($args)
    {
    }

    /**
     * Delete specified comment.
     *
     * @param int   $id ID of comment to delete.
     * @param array $args Optional. Extra parameters to pass to WordPress.
     */
    public function deleteComment($id, $args = [])
    {
    }
}
