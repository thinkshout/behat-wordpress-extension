<?php
namespace PaulGibbs\WordPressBehatExtension\Context\Initializer;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;

use PaulGibbs\WordPressBehatExtension\Context\WordPressContext;

class WordPressContextInitializer implements ContextInitializer
{
    private $wordpressParams;
    private $minkParams;
    private $basePath;
    private $context;

    public function __construct($wordpressParams, $minkParams, $basePath)
    {
        $this->basePath        = $basePath;
        $this->minkParams      = $minkParams;
        $this->wordpressParams = $this->sanitiseWordpressParams($wordpressParams);
    }

    public function initializeContext(Context $context)
    {
        $this->context = $context;
    }

    /**
     * Sanitise the supplied WordPress configuration variables.
     *
     * @param array $params
     * @return array
     */
    protected function sanitiseWordpressParams($params)
    {
        $params['url'] = filter_var( $params['url'], FILTER_SANITIZE_URL );

        // Fetch WP database credentials if not set.
        if (! $params['db_name'] || ! $params['db_username'] || ! $params['db_password'])
        {
            $path     = dirname(__FILE__) . '/../../../../' . $params['path'] . '/';
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
            } elseif (@file_exists($path . '../wp-tests-config.php') && ! @file_exists($path . '../wp-settings.php'))
            {
                $wpConfig = $path . '../wp-tests-config.php';
            }

            if ($wpConfig && is_readable($wpConfig))
            {
                $wpConfig = file_get_contents( $wpConfig );

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
