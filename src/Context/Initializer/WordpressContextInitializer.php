<?php
namespace PaulGibbs\WordpressBehatExtension\Context\Initializer;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use PaulGibbs\WordpressBehatExtension\Context\WordpressContext;

class WordpressContextInitializer implements ContextInitializer
{
    /**
     * WordPress context.
     *
     * @var WordpressContext
     */
    protected $wordpress = null;

    /**
     * WordPress context parameters.
     *
     * @var array
     */
    protected $params = [];


    /**
     * Constructor.
     *
     * @param array $wordpressParams
     */
    public function __construct($wordpressParams)
    {
        $this->params = $wordpressParams;
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

        $this->wordpress = $context;
        $this->wordpress->setWordpressParameters($this->params);
    }
}
