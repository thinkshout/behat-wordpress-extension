<?php
namespace PaulGibbs\WordpressBehatExtension\Context;

use Behat\MinkExtension\Context\MinkContext;
use PaulGibbs\WordpressBehatExtension\Context\Initializer\WordpressContextInitializer;

class WordpressContext extends MinkContext
{
    protected $contextInitializer;

    /**
     * Set reference back to this context's initializer.
     *
     * @param array $parameters
     */
    public function setContextInitializer(WordpressContextInitializer $initializer)
    {
        $this->contextInitializer = $initializer;
    }
}
