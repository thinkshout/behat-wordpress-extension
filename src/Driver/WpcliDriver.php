<?php
namespace PaulGibbs\WordpressBehatExtension\Driver;

/**
 * Connect the Behat environment to WordPress with WP-CLI.
 */
class WpcliDriver extends BaseDriver
{
    /**
     * The name of a WP-CLI alias for tests requiring shell access.
     *
     * @var string
     */
    protected $wpcli_alias = '';


    /**
     * Constructor.
     *
     * @param string $wpcli_alias A WP-CLI alias.
     */
    public function __construct($wpcli_alias)
    {
        $this->wpcli_alias = ltrim($wpcli_alias, '@');
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
        $alias = "@{$this->wpcli_alias}";
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
