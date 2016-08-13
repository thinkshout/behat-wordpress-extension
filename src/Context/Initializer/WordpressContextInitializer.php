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
     * @var array
     */
    protected $params = [];


    /**
     * Constructor.
     *
     * @param array $wordpressParams
     * @param array $minkParams
     * @param array $basePath
     */
    public function __construct($wordpressParams, $minkParams, $basePath)
    {
        $this->params = array(
            'basePath'  => $basePath,
            'mink'      => $minkParams,
            'wordpress' => $this->sanitiseWordpressParams($wordpressParams),
        );
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
        $this->maybeInstallWordpress();
    }

    /**
     * Install WordPress. Maybe.
     */
    public function maybeInstallWordpress()
    {
    	//todo chnge
        $bin = $this->getParameters()['wordpress']['composer_bin_dir'];

        // Drop all tables.
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

    /**
     * Set a configuration parameter.
     *
     * @param string $key
     * @param mixed $value
     */
    public function setParameter($key, $value)
    {
        $this->params['wordpress'][$key] = $value;
    }

    /**
     * Get configuration parameters.
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->params;
    }
}
