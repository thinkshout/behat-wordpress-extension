<?php
namespace PaulGibbs\WordpressBehatExtension\Driver;

/**
 * WordPress driver interface.
 *
 * A driver represents and manages the connection between the Behat environment and a WordPress site.
 */
interface DriverInterface
{
    /**
     * Has the driver has been bootstrapped?
     */
    public function isBootstrapped();

    /**
     * Set up anything required for the driver.
     *
     * Called when the driver is used for the first time.
     */
    public function bootstrap();

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
     * @param string $theme
     */
    public function switchTheme($theme);

    /**
     * Create a term in a taxonomy.
     *
     * @param string $term
     * @param string $taxonomy
     * @param array  $args     Optional. Set the values of the new term.
     * @return array {
     *     @type int    $id   Term ID.
     *     @type string $slug Term slug.
     * }
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
     * @return array {
     *     @type int    $id   Content ID.
     *     @type string $slug Content slug.
     * }
     */
    public function createContent($args);

    /**
     * Delete specified content.
     *
     * @param int   $id   ID of content to delete.
     * @param array $args Optional. Extra parameters to pass to WordPress.
     */
    public function deleteContent($id, $args = []);

    /**
     * Create a comment.
     *
     * @param array $args Set the values of the new comment.
     * @return array {
     *     @type int $id Content ID.
     * }
     */
    public function createComment($args);

    /**
     * Delete specified comment.
     *
     * @param int   $id   ID of comment to delete.
     * @param array $args Optional. Extra parameters to pass to WordPress.
     */
    public function deleteComment($id, $args = []);

    /**
     * Export WordPress database.
     *
     * @return string Absolute path to database SQL file.
     */
    public function exportDatabase();

    /**
     * Import WordPress database.
     *
     * @param string $import_file Relative path and filename of SQL file to import.
     */
    public function importDatabase($import_file);

    /**
     * Create a user.
     *
     * @param string $user_login User login name.
     * @param string $user_email User email address.
     * @param array  $args       Optional. Extra parameters to pass to WordPress.
     * @return array {
     *     @type int    $id   User ID.
     *     @type string $slug User slug (nicename).
     * }
     */
    public function createUser($user_login, $user_email, $args = []);

    /**
     * Delete a user.
     *
     * @param int   $id   ID of user to delete.
     * @param array $args Optional. Extra parameters to pass to WordPress.
     */
    public function deleteUser($id, $args = []);

    /**
     * Start a database transaction.
     */
    public function startTransaction();

    /**
     * End (rollback) a database transaction.
     */
    public function endTransaction();
}
