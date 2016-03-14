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

    public function initializeContext(Context $context)
    {
        if (!$context instanceof WordPressContext) {
            return;
        }
    }
}
