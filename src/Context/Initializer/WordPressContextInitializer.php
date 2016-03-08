<?php
namespace paulgibbs\WordPress\Behat\Context\Initializer;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;

use paulgibbs\WordPress\Behat\Context\WordPressContext;

class WordPressContextInitializer implements ContextInitializer
{
    private $wordpressParams;
    private $minkParams;
    private $basePath;

    public function __construct($wordpressParams, $minkParams, $basePath)
    {
        $this->basePath        = $basePath;
        $this->minkParams      = $minkParams;
        $this->wordpressParams = $wordpressParams;
    }

    /**
     * setup the wordpress environment / stack if the context is a wordpress context
     *
     * @param Context $context
     */
    public function initializeContext(Context $context)
    {
        if (!$context instanceof WordPressContext) {
            return;
        }

        $this->prepareEnvironment();
        $this->loadWordPress();
    }

    protected function prepareEnvironment()
    {
        $parts                      = parse_url($this->minkParams['base_url']);
        $_SERVER['HTTP_HOST']       = $parts['host'] . (isset($parts['port']) ? ':' . $parts['port'] : '');
        $_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
        $PHP_SELF                   = $GLOBALS['PHP_SELF'] = $_SERVER['PHP_SELF'] = '/index.php';
    }

    protected function loadWordPress()
    {
        $wordpress = $this->wordpressParams['path'] . '/wp-load.php';

        if (!file_exists($wordpress))
        {
            return;
        }

        require_once $wordpress;
    }
}
