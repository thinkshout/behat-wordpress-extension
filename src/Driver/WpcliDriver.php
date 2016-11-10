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
    public function run($command, $subcommand, $raw_arguments = array())
    {
        // TODO: update for path.
        $alias = "@{$this->alias}";
        $path  = $this->path;
        $url   = $this->url;

        $arguments  = '';
        $cmd_output = array();
        $exit_code  = 0;

        foreach ($raw_arguments as $name => $value) {
            if (is_int($name)) {
                $arguments .= "{$value} ";
            } else {
                $arguments .= sprintf('%s=%s ', $name, escapeshellarg($value));
            }
        }

        exec("wp {$alias} {$command} {$subcommand} {$arguments} --no-color", $cmd_output, $exit_code);

        return compact('cmd_output', 'exit_code');
    }
}
