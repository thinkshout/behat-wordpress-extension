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
    public function pluginActivate($plugin);

    /**
     * Deactivate a plugin.
     *
     * @param string $plugin
     */
    public function pluginDeactivate($plugin);

    /**
     * Switch active theme.
     *
     * @param string $plugin
     */
    public function switchTheme($plugin);
}
