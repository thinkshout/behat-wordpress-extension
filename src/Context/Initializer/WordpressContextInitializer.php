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
        $this->params         = $config;
        $this->params['mink'] = $mink_params;
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
    }
}
