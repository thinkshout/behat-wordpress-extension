<?php
namespace PaulGibbs\WordpressBehatExtension\Driver;

use RuntimeException,
    UnexpectedValueException;

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
     * Path to WordPress' files.
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
     * @param string $path  Absolute path to WordPress site's files. This or $alias must be not falsey.
     * @param string $url   WordPress site URL.
     */
    public function __construct($alias, $path, $url)
    {
        $this->alias = ltrim($alias, '@');
        $this->path  = realpath($path);
        $this->url   = rtrim(filter_var($url, FILTER_SANITIZE_URL), '/');
    }

    /**
     * Set up anything required for the driver.
     *
     * Called when the driver is used for the first time.
     */
    public function bootstrap()
    {
        $status = $this->wpcli('core', 'is-installed')['exit_code'];

        if ($status !== 0) {
            throw new RuntimeException('WP-CLI driver cannot find WordPress. Check "path" and/or "alias" settings.');
        }

        $this->is_bootstrapped = true;
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
    public function wpcli($command, $subcommand, $raw_arguments = [])
    {
        $arguments = '';

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

        // Support Windows.
        if (DIRECTORY_SEPARATOR === '\\') {
            $binary = 'wp.bat';
        } else {
            $binary = 'wp';
        }

        $cmd_output = [];
        $exit_code  = 0;
        $wpcli_args = '--no-color --require=src/WpcliLogger.php';

        // Query WP-CLI.
        exec("{$binary} {$config} {$command} {$subcommand} {$arguments} {$wpcli_args} 2>&1", $cmd_output, $exit_code);
        $cmd_output = implode(PHP_EOL, $cmd_output);

        if ($cmd_output) {
            // Any output is bad.
            throw new UnexpectedValueException("WP-CLI driver query failure: {$cmd_output}");
        }

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
    public function activatePlugin($plugin)
    {
        $this->wpcli('plugin', 'activate', [$plugin]);
    }

    /**
     * Deactivate a plugin.
     *
     * @param string $plugin
     */
    public function deactivatePlugin($plugin)
    {
        $this->wpcli('plugin', 'deactivate', [$plugin]);
    }

    /**
     * Switch active theme.
     *
     * @param string $theme
     */
    public function switchTheme($theme)
    {
        $this->wpcli('theme', 'activate', [$theme]);
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
        $whitelist  = ['description', 'parent', 'slug'];
        $wpcli_args = [$taxonomy, $term, '--porcelain'];

        foreach ($whitelist as $option) {
            if (isset($args[$option])) {
                $wpcli_args["--{$option}"] = $args[$option];
            }
        }

        return (int) $this->wpcli('term', 'create', $wpcli_args)['cmd_output'];
    }

    /**
     * Delete a term from a taxonomy.
     *
     * @param int    $term_id
     * @param string $taxonomy
     */
    public function deleteTerm($term_id, $taxonomy)
    {
        $this->wpcli('term', 'delete', [$taxonomy, $term_id]);
    }

    /**
     * Create content.
     *
     * @param array $args Set the values of the new content item.
     * @return int Content ID.
     */
    public function createContent($args)
    {
        $wpcli_args = ['--porcelain'];
        $whitelist  = array(
            'ID', 'post_author', 'post_date', 'post_date_gmt', 'post_content', 'post_content_filtered', 'post_title',
            'post_excerpt', 'post_status', 'post_type', 'comment_status', 'ping_status', 'post_password', 'post_name',
            'to_ping', 'pinged', 'post_modified', 'post_modified_gmt', 'post_parent', 'menu_order', 'post_mime_type',
            'guid', 'post_category', 'tax_input', 'meta_input',
        );

        foreach ($whitelist as $option) {
            if (isset($args[$option])) {
                $wpcli_args["--{$option}"] = $args[$option];
            }
        }

        return (int) $this->wpcli('post', 'create', $wpcli_args)['cmd_output'];
    }

    /**
     * Delete specified content.
     *
     * @param int   $id   ID of content to delete.
     * @param array $args Optional. Extra parameters to pass to WordPress.
     */
    public function deleteContent($id, $args = [])
    {
        $wpcli_args = [$id];
        $whitelist  = ['force', 'defer-term-counting'];

        foreach ($whitelist as $option) {
            if (isset($args[$option])) {
                $wpcli_args[] = "--{$option}";
            }
        }

        $this->wpcli('post', 'delete', $wpcli_args);
    }

    /**
     * Create a comment.
     *
     * @param array $args Set the values of the new comment.
     * @return int Comment ID.
     */
    public function createComment($args)
    {
        $wpcli_args = ['--porcelain'];
        $whitelist  = array(
            'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_date',
            'comment_date_gmt', 'comment_parent', 'comment_post_ID', 'user_id', 'comment_agent', 'comment_author_IP',
        );

        foreach ($whitelist as $option) {
            if (isset($args[$option])) {
                $wpcli_args["--{$option}"] = $args[$option];
            }
        }

        return (int) $this->wpcli('comment', 'create', $wpcli_args)['cmd_output'];
    }

    /**
     * Delete specified comment.
     *
     * @param int   $id   ID of comment to delete.
     * @param array $args Optional. Extra parameters to pass to WordPress.
     */
    public function deleteComment($id, $args = [])
    {
        $wpcli_args = [$id];
        $whitelist  = ['force'];

        foreach ($whitelist as $option) {
            if (isset($args[$option])) {
                $wpcli_args[] = "--{$option}";
            }
        }

        $this->wpcli('comment', 'delete', $wpcli_args);
    }

    /**
     * Export WordPress database.
     *
     * @return string Absolute path to database SQL file.
     */
    public function exportDatabase()
    {
        while (true) {
            $filename = uniqid('database-', true) . '.sql';

            if (! file_exists(getcwd() . "/{$filename}")) {
                break;
            }
        }

        // Protect against WP-CLI changing the filename.
        $filename = $this->wpcli('db', 'export', [$filename, '--porcelain'])['cmd_output'];

        return getcwd() . "/{$filename}";
    }

    /**
     * Import WordPress database.
     *
     * @param string $filename Absolute path to database SQL file to import.
     */
    public function importDatabase($filename)
    {
        $filename = getcwd() . "/{$filename}";
        $this->wpcli('db', 'import', [$filename]);
    }
}

