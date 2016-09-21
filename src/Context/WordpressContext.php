<?php
namespace PaulGibbs\WordpressBehatExtension\Context;

use Behat\MinkExtension\Context\MinkContext,
    Behat\Behat\Context\SnippetAcceptingContext,
    Behat\Gherkin\Node\TableNode,
    PaulGibbs\WordpressBehatExtension\Context\Initializer\WordpressContextInitializer,
    Exception;

class WordpressContext extends MinkContext implements SnippetAcceptingContext
{
    /**
     * WordPress context parameters.
     *
     * @var array
     */
    protected $wordpressParameters = null;


    /*
     * Sets parameters provided for WordPress.
     *
     * @param array $parameters
     */
    public function setWordpressParameters(array $parameters)
    {
        $this->wordpressParameters = $parameters;
    }

    /**
     * Return the parameters provided for WordPress.
     *
     * @return array
     */
    public function getWordpressParameters()
    {
        return $this->wordpressParameters;
    }

    /**
     * Set a specific WordPress parameter.
     *
     * @param string $name  The key of the parameter.
     * @param mixed  $value The value of the parameter.
     */
    public function setWordpressParameter($name, $value)
    {
        $this->wordpressParameters[$name] = $value;
    }

    /**
     * Return a specific WordPress parameter.
     *
     * @param string $name
     * @return mixed
     */
    public function getWordpressParameter($name)
    {
        return isset($this->wordpressParameters[$name]) ? $this->wordpressParameters[$name] : null;
    }


    /*
     * Behat step definitions.
     */

    /**
     * Install WordPress, with optional plugins.
     *
     * @Given I have a WordPress multisite
     * @Given I have a WordPress site
     *
     * @param string Optional. Type of WordPress, either "site" (default) or "multisite".
     */
    public function installWordpress($wordpress_type = 'site')
    {
        $cmd = sprintf(
            'wp --path=%s --url=%s core is-installed',
            escapeshellarg($this->getWordpressParameter('path')),
            escapeshellarg($this->getMinkParameter('base_url'))
        );
        exec($cmd, $cmd_output, $exit_code);

        if ($exit_code === 0) {
            // This means WordPress is installed. Let's remove its databases.
            $cmd = sprintf(
                'wp --path=%s --url=%s db reset --yes',
                escapeshellarg($this->getWordpressParameter('path')),
                escapeshellarg($this->getMinkParameter('base_url'))
            );
            exec($cmd);
        }

        $cmd = sprintf(
            'wp --path=%s --url=%s core install --title=%s --admin_user=%s --admin_password=%s --admin_email=%s --skip-email',
            escapeshellarg($this->getWordpressParameter('path')),
            escapeshellarg($this->getMinkParameter('base_url')),
            escapeshellarg('Test Site'),
            escapeshellarg('wordpress'),
            escapeshellarg('wordpress'),
            escapeshellarg('wordpress@example.com')
        );
        exec($cmd, $cmd_output);

        if ($cmd_output[0] !== 'Success: WordPress installed successfully.') {
            throw new Exception('Error installing WordPress: ' . implode(PHP_EOL, $cmd_output));
            die;
        }
    }

    /**
     * Install WordPress plugins.
     *
     * @Given I have these plugins:
     * @Given I have this plugin:
     *
     * @param TableNode {
     *     A table of plugins.
     *
     *     @type string $plugin Plugin slug.
     *     @type string $status Whether to active the plugin. Either "enabled" or "disabled".
     * }
     */
    public function installPlugins(TableNode $plugins)
    {
        foreach ($plugins as $plugin) {
            $cmd = sprintf(
                'wp --path=%1$s --url=%2$s plugin is-installed %3$s',
                escapeshellarg($this->getWordpressParameter('path')),
                escapeshellarg($this->getMinkParameter('base_url')),
                $plugin['plugin']
            );
            exec($cmd, $cmd_output, $exit_code);

            // Install the plugin if it's missing.
            if ($exit_code === 1) {
                $cmd = sprintf(
                    'wp --path=%1$s --url=%2$s plugin install %3$s',
                    escapeshellarg($this->getWordpressParameter('path')),
                    escapeshellarg($this->getMinkParameter('base_url')),
                    $plugin['plugin']
                );
                exec($cmd, $cmd_output);

                if (end($cmd_output) !== 'Plugin installed successfully.') {
                    throw new Exception('Error installing plugin: ' . implode(PHP_EOL, $cmd_output));
                    die;
                }
            }

            // Activate/deactivate plugin as required.
            $cmd = sprintf(
                'wp --path=%1$s --url=%2$s plugin %3$s %4$s',
                escapeshellarg($this->getWordpressParameter('path')),
                escapeshellarg($this->getMinkParameter('base_url')),
                ($plugin['status'] === 'enabled') ? 'activate' : 'deactivate',
                $plugin['plugin']
            );
            exec($cmd, $cmd_output);

            if (end($cmd_output) !== "Success: Plugin '" . $plugin['plugin'] . "' activated.") {
                throw new Exception('Error activating/deactivating plugin: ' . implode(PHP_EOL, $cmd_output));
                die;
            }
        }
    }
}
