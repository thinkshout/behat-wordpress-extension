<?php
namespace PaulGibbs\WordpressBehatExtension\Driver;

use RuntimeException;

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
            throw new RuntimeException(sprintf('WordPress API driver cannot find WordPress at %s.', $this->path));
        }

        // "Cry 'Havoc!' and let slip the dogs of war".
        require_once "{$this->path}/wp-blog-header.php";

        $this->is_bootstrapped = true;
    }

    /**
     * Clear object cache.
     */
    public function clearCache()
    {
        wp_cache_flush();
    }

    /**
     * Activate a plugin.
     *
     * @param string $plugin
     */
    public function activatePlugin($plugin)
    {
        if (
            ! file_exists("{$this->path}/wp-admin/includes/plugin.php") ||
            ! file_exists("{$this->path}/wp-admin/includes/plugin-install.php")
        ) {
            throw new RuntimeException('WordPress API driver cannot find expected WordPress files.');
        }

        require_once "{$this->path}/wp-admin/includes/plugin.php";
        require_once "{$this->path}/wp-admin/includes/plugin-install.php";

        $plugin = $this->getPlugin($plugin);
        if ($plugin) {
            activate_plugin($plugin);
        }
    }

    /**
     * Deactivate a plugin.
     *
     * @param string $plugin
     */
    public function deactivatePlugin($plugin)
    {
        if (
            ! file_exists("{$this->path}/wp-admin/includes/plugin.php") ||
            ! file_exists("{$this->path}/wp-admin/includes/plugin-install.php")
        ) {
            throw new RuntimeException('WordPress API driver cannot find expected WordPress files.');
        }

        require_once "{$this->path}/wp-admin/includes/plugin.php";
        require_once "{$this->path}/wp-admin/includes/plugin-install.php";

        $plugin = $this->getPlugin($plugin);
        if (! $plugin) {
            return;
        }

        deactivate_plugins($plugin);
    }

    /**
     * Switch active theme.
     *
     * @param string $theme
     */
    public function switchTheme($theme)
    {
        $the_theme = wp_get_theme($theme);
        if (! $the_theme->exists()) {
            return;
        }

        switch_theme( $the_theme->get_template(), $the_theme->get_stylesheet() );
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
        $args = wp_slash($args);
        $term = wp_slash($term);

        $new_term = wp_insert_term($term, $taxonomy, $args);
        return $new_term['term_id'];
    }

    /**
     * Delete a term from a taxonomy.
     *
     * @param int    $term_id
     * @param string $taxonomy
     */
    public function deleteTerm($term_id, $taxonomy)
    {
        wp_delete_term($term_id, $taxonomy);
    }

    /**
     * Create content.
     *
     * @param array $args Set the values of the new content item.
     * @return int Content ID.
     */
    public function createContent($args)
    {
        $args = wp_slash($args);
        return wp_insert_post($args);
    }

    /**
     * Delete specified content.
     *
     * @param int   $id ID of content to delete.
     * @param array $args Optional. Extra parameters to pass to WordPress.
     */
    public function deleteContent($id, $args = [])
    {
        wp_delete_post($id, isset($args['force']));
    }

    /**
     * Create a comment.
     *
     * @param array $args Set the values of the new comment.
     * @return int Comment ID.
     */
    public function createComment($args)
    {
        return wp_new_comment($args);
    }

    /**
     * Delete specified comment.
     *
     * @param int   $id ID of comment to delete.
     * @param array $args Optional. Extra parameters to pass to WordPress.
     */
    public function deleteComment($id, $args = [])
    {
        wp_delete_comment($id, isset($args['force']));
    }


    /*
     * Internal helpers.
     */

    /**
     * Get information about a plugin.
     *
     * @param string $name
     * @return string Plugin filename and path.
     */
    protected function getPlugin($name)
    {
        foreach ( et_plugins() as $file => $_) {
            if ($file === "{$name}.php" || ($name && $file === $name) || (dirname($file) === $name && $name !== '.')) {
                return $file;
            }
        }

        return '';
    }
}
