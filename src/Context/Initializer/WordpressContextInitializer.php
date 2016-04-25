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

        // DB.
        $this->initializeDatabaseConnection();
        register_shutdown_function(array($this, 'terminateDatabaseConnection'));

        // Environment.
        $this->maybeInstallWordpress();
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

    /**
     * Connect to MySQL.
     */
    public function initializeDatabaseConnection()
    {
        $this->context->setDatabase(mysqli_init());
        $db_settings = $this->getParameters()['wordpress'];

        if (! @mysqli_real_connect(
            $this->context->getDatabase(),
            $db_settings['db_host'],
            $db_settings['db_username'],
            $db_settings['db_password'],
            $db_settings['db_name']
        ))
        {
            die('MySQL connect error: (' . mysqli_connect_errno() . ') ' . mysqli_connect_error() . PHP_EOL);
        }

        mysqli_query(
            $this->context->getDatabase(),
            'SET GLOBAL TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;'
        );
    }

    /**
     * Close MySQL connection.
     */
    public function terminateDatabaseConnection()
    {
        mysqli_query($this->context->getDatabase(), 'SET GLOBAL TRANSACTION ISOLATION LEVEL REPEATABLE READ;');
        mysqli_close($this->context->getDatabase());
    }

    /**
     * Install WordPress. Maybe.
     */
    public function maybeInstallWordpress()
    {
        $bin_dir = $this->getParameters()['wordpress']['composer_bin_dir'];
    }

    /**
     * Sanitise the supplied WordPress configuration variables.
     *
     * @param array $params
     * @return array
     */
    protected function sanitiseWordpressParams($params)
    {
        $params['site_url'] = filter_var( $params['site_url'], FILTER_SANITIZE_URL );

        // Fetch WP database credentials if not set.
        if (! $params['db_name'] || ! $params['db_username'] || ! $params['db_password'] || ! $params['db_host'])
        {
            $path     = dirname(__FILE__) . '/../../../../../../../../../';
            $wpConfig = '';

            /*
             * If wp-config.php exists in the WordPress root, or if it exists in the root and wp-settings.php
             * doesn't, load wp-config.php. The secondary check for wp-settings.php has the added benefit
             * of avoiding cases where the current directory is a nested installation, e.g. / is WordPress(a)
             * and /blog/ is WordPress(b).
             */
            if (@file_exists($path . 'wp-tests-config.php'))
            {
                $wpConfig = $path . 'wp-tests-config.php';
            }
            elseif (@file_exists($path . '../wp-tests-config.php') && ! @file_exists($path . '../wp-settings.php'))
            {
                $wpConfig = $path . '../wp-tests-config.php';
            }

            if ($wpConfig && is_readable($wpConfig))
            {
                $wpConfig = file_get_contents($wpConfig);

                if (preg_match('#^define\( ?\'DB_HOST\', ?\'([^\']*)\' \);$#m', $wpConfig, $matches))
                {
                    $params['db_host'] = $matches[1];
                }

                if (preg_match('#^define\( ?\'DB_NAME\', ?\'([^\']*)\' \);$#m', $wpConfig, $matches))
                {
                    $params['db_name'] = $matches[1];
                }

                if (preg_match('#^define\( ?\'DB_USER\', ?\'([^\']*)\' \);$#m', $wpConfig, $matches))
                {
                    $params['db_username'] = $matches[1];
                }

                if (preg_match('#^define\( ?\'DB_PASSWORD\', ?\'([^\']*)\' \);$#m', $wpConfig, $matches))
                {
                    $params['db_password'] = $matches[1];
                }
            }
        }

        return $params;
    }
}
