<?php
namespace PaulGibbs\WordpressBehatExtension\Context\Initializer;

use Behat\Behat\Context\Context,
    Behat\Behat\Context\Initializer\ContextInitializer;

use PaulGibbs\WordpressBehatExtension\WordpressDriverManager;

/**
 * Behat Context initializer.
 */
class WordpressAwareInitializer implements ContextInitializer
{
    /**
     * WordPress driver manager.
     *
     * @var DrupalDriverManager
     */
    protected $wordpress;

    /**
     * WordPress context parameters.
     *
     * @var array
     */
    protected $parameters = [];


    /**
     * Constructor.
     *
     * @param DrupalDriverManager $wordpress
     * @param array               $wordpressParams
     */
    public function __construct(DrupalDriverManager $wordpress, $wordpressParams)
    {
        $this->wordpress  = $wordpress;
        $this->parameters = $wordpressParams;
    }

    /**
     * Prepare everything that the Context needs.
     *
     * @param Context $context
     */
    public function initializeContext(Context $context)
    {
        if (! $context instanceof WordpressAwareInterface) {
            return;
        }

        $context->setWordpress($this->wordpress);
        $context->setWordpressParameters($this->parameters);
    }
}
