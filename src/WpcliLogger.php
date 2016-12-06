<?php
namespace PaulGibbs\WordpressBehatExtension;

use WP_CLI;
use WP_CLI\Loggers\Quiet as QuietLogger;

/**
 * A logger for WP-CLI that promotes warning messages to errors.
 *
 * Not loaded as part of the Behat extension. Designed to run on WP-CLI via its `--require` parameter.
 */
class WpcliLogger extends QuietLogger
{
    /**
     * Promote WP-CLI warnings to errors.
     *
     * @param string $message
     */
    public function warning($message)
    {
        $this->error($message);
    }
}

WP_CLI::set_logger(new WpcliLogger);
