<?php
namespace PaulGibbs\WordpressBehatExtension\Driver;

/**
 * Connect Behat to WordPress using WP-CLI.
 */
class WpcliDriver extends BaseDriver
{
    /**
     * The name of a WP-CLI alias for tests requiring shell access.
     *
     * @var string
     */
    protected $alias = '';

    /**
     * WP-CLI path (to the WordPress files).
     *
     * @var string
     */
    protected $path = '';

    /**
     * WordPress site URL.
     *
     * @var string
     */
    protected $url = '';


    /**
     * Constructor.
     *
     * @param string $alias WP-CLI alias. This or $path must be not falsey.
     * @param string $path Absolute path to WordPress site files. This or $alias must be not falsey.
     * @param string $url WordPress site URL.
     */
    public function __construct($alias, $path, $url)
    {
        $this->alias = ltrim($alias, '@');
        $this->path  = realpath($path);
        $this->url   = rtrim(filter_var($url, FILTER_SANITIZE_URL), '/');

        if (! $this->alias && ! $this->path) {
            throw new \RuntimeException('WP-CLI driver requires an `alias` or `root` path.');
        }
    }

    /**
     * Execute a WP-CLI command.
     *
     * @param string $command       Command name.
     * @param string $subcommand    Subcommand name.
     * @param array  $raw_arguments Optional. Associative array of arguments for the command.
     * @return array {
     *     WP-CLI command results.
     *
     *     @type array $cmd_output Command output.
     *     @type int   $exit_code  Returned status code of the executed command.
     * }
     */
    public function wpcli($command, $subcommand, $raw_arguments = array())
    {
        $arguments  = '';
        $cmd_output = array();
        $exit_code  = 0;

        // Build parameter list.
        foreach ($raw_arguments as $name => $value) {
            if (is_int($name)) {
                $arguments .= "{$value} ";
            } else {
                $arguments .= sprintf('%s=%s ', $name, escapeshellarg($value));
            }
        }

        // Support WP-CLI environment alias, or path and URL.
        if ($this->alias) {
            $config = "@{$this->alias}";
        } else {
            // TODO: review best practice with escapeshellcmd() here, and impact on metacharactes.
            $config = sprintf('--path=%s --url=%s', escapeshellarg($this->path), escapeshellarg($this->url));
        }

        exec("wp {$config} {$command} {$subcommand} {$arguments} --no-color", $cmd_output, $exit_code);

        return compact('cmd_output', 'exit_code');
    }

    /**
     * Clear object cache.
     */
    public function clearCache()
    {
        $this->wpcli('cache', 'flush');
    }

    /**
     * Activate a plugin.
     *
     * @param string $plugin
     */
    public function pluginActivate($plugin)
    {
        $this->wpcli('plugin', 'activate', array($plugin));
    }

    /**
     * Deactivate a plugin.
     *
     * @param string $plugin
     */
    public function pluginDeactivate($plugin)
    {
        $this->wpcli('plugin', 'deactivate', array($plugin));
    }

    /**
     * Switch active theme.
     *
     * @param string $theme
     */
    public function switchTheme($theme)
    {
        $this->wpcli('theme', 'activate', array($theme));
    }
}
