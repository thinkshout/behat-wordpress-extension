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
        $this->wordpressParams = $wordpressParams;
    }

    public function initializeContext(Context $context)
    {
        $this->context = $context;
    }
}
