<?php
namespace PaulGibbs\WordpressBehatExtension\Driver;

/**
 * Connect Behat to WordPress using just a web browser.
 */
class BlackboxDriver extends BaseDriver
{
    /**
     * Constructor.
     */
    public function __construct()
    {
    }

    /**
     * Clear object cache.
     */
    public function clearCache()
    {
        // Not supported by the blackbox driver.
    }

    /**
     * Activate a plugin.
     *
     * @param string $plugin
     */
    public function activatePlugin($plugin)
    {
        // Not supported by the blackbox driver.
    }

    /**
     * Deactivate a plugin.
     *
     * @param string $plugin
     */
    public function deactivatePlugin($plugin)
    {
        // Not supported by the blackbox driver.
    }

    /**
     * Switch active theme.
     *
     * @param string $theme
     */
    public function switchTheme($theme)
    {
        // Not supported by the blackbox driver.
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
        // Not supported by the blackbox driver.
        return 0;
   }

    /**
     * Delete a term from a taxonomy.
     *
     * @param int    $term_id
     * @param string $taxonomy
     */
    public function deleteTerm($term_id, $taxonomy)
    {
        // Not supported by the blackbox driver.
    }

    /**
     * Create content.
     *
     * @param array $args Set the values of the new content item.
     * @return int Content ID.
     */
    public function createContent($args)
    {
        // Not supported by the blackbox driver.
        return 0;
    }

    /**
     * Delete specified content.
     *
     * @param int   $id ID of content to delete.
     * @param array $args Optional. Extra parameters to pass to WordPress.
     */
    public function deleteContent($id, $args = [])
    {
        // Not supported by the blackbox driver.
    }

    /**
     * Create a comment.
     *
     * @param array $args Set the values of the new comment.
     * @return int Comment ID.
     */
    public function createComment($args)
    {
        // Not supported by the blackbox driver.
        return 0;
    }

    /**
     * Delete specified comment.
     *
     * @param int   $id ID of comment to delete.
     * @param array $args Optional. Extra parameters to pass to WordPress.
     */
    public function deleteComment($id, $args = [])
    {
        // Not supported by the blackbox driver.
    }

    /**
     * Export WordPress database.
     *
     * @return string Absolute path to database SQL file.
     */
    public function exportDatabase()
    {
        // Not supported by the blackbox driver.
        return '';
    }

    /**
     * Import WordPress database.
     *
     * @param string $filename Absolute path to database SQL file to import.
     */
    public function importDatabase($filename)
    {
        // Not supported by the blackbox driver.
    }
}

