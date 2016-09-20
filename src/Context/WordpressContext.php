<?php
namespace PaulGibbs\WordpressBehatExtension\Context;

use Behat\MinkExtension\Context\MinkContext;
use PaulGibbs\WordpressBehatExtension\Context\Initializer\WordpressContextInitializer;

class WordpressContext extends MinkContext
{
    /**
     * @var WordpressContextInitializer
     */
    protected $initializer = null;


    /**
     * Set reference back to this context's initializer.
     *
     * @param array $parameters
     */
    public function setContextInitializer(WordpressContextInitializer $initializer)
    {
        $this->initializer = $initializer;
    }

    /**
     * Install WordPress, with optional plugins.
     *
     * @Given /^I have a WordPress (multisite|site)$/
     * @Given /^I have a WordPress (multisite|site) with these plugins: (.+)$/
     *
     * @param string Optional. Type of WordPress configuration, either "site" (default) or "multisite".
     * @param string Optional. Comma-seperated list of plugins to install.
     */
    public function installWordpress($wordpress_type = 'site', $plugins = '')
    {
        $cmd = sprintf(
            'wp --path=%s --url=%s core is-installed',
            escapeshellarg($this->initializer->params['path']),
            escapeshellarg($this->initializer->params['url'])
        );
        exec($cmd, $cmd_output, $exit_code);

        if ($exit_code === 0) {
            // This means WordPress is installed. Let's remove its databases.
            $cmd = sprintf(
                'wp --path=%s --url=%s db reset --yes',
                escapeshellarg($this->initializer->params['path']),
                escapeshellarg($this->initializer->params['url'])
            );
            exec($cmd);
        }

        $cmd = sprintf(
            'wp --path=%s --url=%s core install --title=%s --admin_user=%s --admin_password=%s --admin_email=%s --skip-email',
            escapeshellarg($this->initializer->params['path']),
            escapeshellarg($this->initializer->params['url']),
            escapeshellarg('Test Site'),
            escapeshellarg('wordpress'),
            escapeshellarg('wordpress'),
            escapeshellarg('wordpress@example.com')
        );
        exec($cmd, $cmd_output);

        if ($cmd_output[0] !== 'Success: WordPress installed successfully.') {
            throw new \Exception('Error installing WordPress: ' . implode(PHP_EOL, $cmd_output));
            die;
        }

        if ($plugins) {
            $this->installPlugins($plugins);
        }
    }

    /**
     * Install WordPpess plugins.
     *
     * @param string Comma-seperated list of plugins to install.
     */
    public function installPlugins($plugins)
    {
        $plugins = array_filter(array_map('trim', explode(',', $plugins)));

        foreach ($plugins as $plugin) {
            $cmd = sprintf(
                'wp --path=%s --url=%s plugin is-installed %s',
                escapeshellarg($this->initializer->params['path']),
                escapeshellarg($this->initializer->params['url']),
                $plugin
            );
            exec($cmd, $cmd_output, $exit_code);

            if ($exit_code === 0) {
                // Plugin is already installed.
                continue;
            }

            $cmd = sprintf(
                'wp --path=%s --url=%s plugin install %s --activate',
                escapeshellarg($this->initializer->params['path']),
                escapeshellarg($this->initializer->params['url']),
                $plugin
            );
            exec($cmd, $cmd_output);

            if (end($cmd_output) !== "Success: Plugin '{$plugin}' activated.") {
                throw new \Exception('Error installing plugin: ' . implode(PHP_EOL, $cmd_output));
                die;
            }
        }
    }

    /**
     * Begin a database transaction before the scenario is run.
     *
     * @BeforeScenario
     */
    public function startTransaction()
    {
    	//todo
    }

    /**
     * Roll back database transaction after the scenario runs.
     *
     * @AfterScenario
     */
    public function rollbackTransaction()
    {
    	//todo
    }
}
