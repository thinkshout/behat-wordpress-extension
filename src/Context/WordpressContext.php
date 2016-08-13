<?php
namespace PaulGibbs\WordpressBehatExtension\Context;

use Behat\MinkExtension\Context\MinkContext;
use PaulGibbs\WordpressBehatExtension\Context\Initializer\WordpressContextInitializer;

class WordpressContext extends MinkContext
{
    /**
     * @var WordpressContextInitializer
     */
    protected $contextInitializer = null;


    /**
     * Set reference back to this context's initializer.
     *
     * @param array $parameters
     */
    public function setContextInitializer(WordpressContextInitializer $initializer)
    {
        $this->contextInitializer = $initializer;
    }

    /**
     * Begin a database transaction before the scenario is run.
     *
     * @BeforeScenario
     */
    public function startTransaction()
    {
    	//todo
    }

    /**
     * Roll back database transaction after the scenario runs.
     *
     * @AfterScenario
     */
    public function rollbackTransaction()
    {
    	//todo
    }
}
