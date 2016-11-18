<?php
namespace PaulGibbs\WordpressBehatExtension\Driver;

/**
 * WordPress Driver interface.
 *
 * A Driver represents and manages the connection between the Behat environment and a WordPress site.
 */
interface DriverInterface
{
    /**
     * Clear object cache.
     */
    public function clearCache();

    /**
     * Activate a plugin.
     *
     * @param string $plugin
     */
    public function activatePlugin($plugin);

    /**
     * Deactivate a plugin.
     *
     * @param string $plugin
     */
    public function deactivatePlugin($plugin);

    /**
     * Switch active theme.
     *
     * @param string $plugin
     */
    public function switchTheme($plugin);

    /**
     * Create a term in a taxonomy.
     *
     * @param string $term
     * @param string $taxonomy
     * @param array  $args Optional. Set the values of the new term.
     * @return int Term ID.
     */
   public function createTerm($term, $taxonomy, $args = []);

    /**
     * Delete a term from a taxonomy.
     *
     * @param int    $term_id
     * @param string $taxonomy
     */
    public function deleteTerm($term_id, $taxonomy);

    /**
     * Create content.
     *
     * @param array $args Set the values of the new content item.
     * @return int Content ID.
     */
    public function createContent($args);

    /**
     * Delete specified content.
     *
     * @param int   $id ID of content to delete.
     * @param array $args Optional. Extra parameters to pass to WordPress.
     */
    public function deleteContent($id, $args = []);

    /**
     * Export WordPress database.
     *
     * @return string Absolute path to database SQL file.
     */
    public function exportDatabase();

    /**
     * Import WordPress database.
     *
     * @param string $import_file Absolute path to database SQL file to import.
     */
    public function importDatabase($import_file);
}
