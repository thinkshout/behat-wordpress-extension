<?php
namespace PaulGibbs\WordpressBehatExtension\Driver;

use PaulGibbs\WordpressBehatExtension\Exception\UnsupportedDriverActionException;

/**
 * Common base class for WordPress drivers.
 *
 * A driver represents and manages the connection between the Behat environment and a WordPress site.
 */
abstract class BaseDriver implements DriverInterface
{
    /**
     * Clear object cache.
     */
    public function clearCache()
    {
        throw new UnsupportedDriverActionException('clear cache in ' . self::class);
    }

    /**
     * Activate a plugin.
     *
     * @param string $plugin
     */
    public function activatePlugin($plugin)
    {
        throw new UnsupportedDriverActionException('activate plugins in ' . self::class);
    }

    /**
     * Deactivate a plugin.
     *
     * @param string $plugin
     */
    public function deactivatePlugin($plugin)
    {
        throw new UnsupportedDriverActionException('deactivate plugins in ' . self::class);
    }

    /**
     * Switch active theme.
     *
     * @param string $plugin
     */
    public function switchTheme($plugin)
    {
        throw new UnsupportedDriverActionException('switch themes in ' . self::class);
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
        throw new UnsupportedDriverActionException('create terms in ' . self::class);
    }

    /**
     * Delete a term from a taxonomy.
     *
     * @param int    $term_id
     * @param string $taxonomy
     */
    public function deleteTerm($term_id, $taxonomy)
    {
        throw new UnsupportedDriverActionException('delete terms in ' . self::class);
    }

    /**
     * Create content.
     *
     * @param array $args Set the values of the new content item.
     * @return int Content ID.
     */
    public function createContent($args)
    {
        throw new UnsupportedDriverActionException('create content in ' . self::class);
    }

    /**
     * Delete specified content.
     *
     * @param int   $id ID of content to delete.
     * @param array $args Optional. Extra parameters to pass to WordPress.
     */
    public function deleteContent($id, $args = [])
    {
        throw new UnsupportedDriverActionException('delete content in ' . self::class);
    }

    /**
     * Create a comment.
     *
     * @param array $args Set the values of the new comment.
     * @return int Content ID.
     */
    public function createComment($args)
    {
        throw new UnsupportedDriverActionException('create comments in ' . self::class);
    }

    /**
     * Delete specified comment.
     *
     * @param int   $id ID of comment to delete.
     * @param array $args Optional. Extra parameters to pass to WordPress.
     */
    public function deleteComment($id, $args = [])
    {
        throw new UnsupportedDriverActionException('delete comments in ' . self::class);
    }

    /**
     * Export WordPress database.
     *
     * @return string Absolute path to database SQL file.
     */
    public function exportDatabase()
    {
        throw new UnsupportedDriverActionException('export the database in ' . self::class);
    }

    /**
     * Import WordPress database.
     *
     * @param string $import_file Absolute path to database SQL file to import.
     */
    public function importDatabase($import_file)
    {
        throw new UnsupportedDriverActionException('import the database in ' . self::class);
    }
}
