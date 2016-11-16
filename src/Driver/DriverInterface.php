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
