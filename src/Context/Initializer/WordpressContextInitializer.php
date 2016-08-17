<?php
namespace PaulGibbs\WordpressBehatExtension\Context\Initializer;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use PaulGibbs\WordpressBehatExtension\Context\WordpressContext;

class WordpressContextInitializer implements ContextInitializer
{
    /**
     * @var WordpressContext
     */
    protected $context = null;

    /**
     * Extension parameters.
     *
     * @todo add a getter
     *
     * @var array
     */
    public $params = [];


    /**
     * Constructor.
     *
     * @param array $config
     * @param array $mink_params
     * @param array $path
     */
    public function __construct($config, $mink_params, $path)
    {
        $this->params = $config;

        $this->params['mink']   = $mink_params;
        $this->params['binDir'] = "{$path}/vendor/bin";
    }

    /**
     * Prepare everything that the Context needs.
     *
     * @param Context $context
     */
    public function initializeContext(Context $context)
    {
        if (! $context instanceof WordpressContext) {
            return;
        }

        $this->context = $context;
        $this->context->setContextInitializer($this);

        // Environment.
        $this->installWordpress();
    }

    /**
     * Install WordPress.
     */
    public function installWordpress()
    {
        $cmd = sprintf(
            'wp --path=%s --url=%s core is-installed',
            escapeshellarg($this->params['path']),
            escapeshellarg($this->params['url'])
        );
        exec($cmd, $cmd_output, $exit_code);

        if ($exit_code === 0) {
            // This means WordPress is installed. Let's remove it.
            $cmd = sprintf(
                'wp --path=%s --url=%s db reset --yes',
                escapeshellarg($this->params['path']),
                escapeshellarg($this->params['url'])
            );
            exec($cmd);
        }

        $cmd = sprintf(
            'wp --path=%s --url=%s core install --title=%s --admin_user=%s --admin_password=%s --admin_email=%s --skip-email',
            escapeshellarg($this->params['path']),
            escapeshellarg($this->params['url']),
            escapeshellarg('Test Site'),
            escapeshellarg('admin'),
            escapeshellarg('admin'),
            escapeshellarg('admin@example.com')
        );
        exec($cmd, $cmd_output);

        if ($cmd_output[0] !== 'Success: WordPress installed successfully.') {
            throw new \Exception('Error installing WordPress: ' . implode( PHP_EOL, $cmd_output ) );
            die;
        }
    }

    function asdfs()
    {
        $tables = mysqli_query($this->context->getDatabase(), 'SHOW TABLES');
        while ($table = mysqli_fetch_row($tables)) {
            mysqli_query($this->context->getDatabase(), "DROP TABLE IF EXISTS {$table[0]}");
        }

        // Install WordPress.
        $status = exec(
            sprintf(
                "{$bin}/wp core install --url=%s --admin_user=%s --admin_password=%s --admin_email=%s --skip-email --title=%s --require=%s",
                '127.0.0.1',
                'admin',
                'admin',
                'admin@example.com',
                'cool-site',
                $this->getParameters()['wordpress']['wpcli_bootstrap']
            )
        );
        if ($status !== 'Success: WordPress installed successfully.') {
            die('Error installing WordPress: ' . $status);
        }
        die;
    }
}
