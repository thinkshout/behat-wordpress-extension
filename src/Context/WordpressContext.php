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
     * MySQL connection handle.
     *
     * @var mysqli_init
     */
    protected $mysqli = null;


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
     * Set MySQL connection handle.
     *
     * @param mysqli $handle
     */
    public function setDatabase(\mysqli $handle)
    {
        $this->mysqli = $handle;
    }

    /**
     * Get MySQL connection handle.
     *
     * @return mysqli
     */
    public function getDatabase()
    {
        return $this->mysqli;
    }

    /**
     * Begin a database transaction before the scenario is run.
     *
     * @BeforeScenario
     */
    public function startTransaction()
    {
        mysqli_query($this->mysqli, 'START TRANSACTION;');
    }

    /**
     * Roll back database transaction after the scenario runs.
     *
     * @AfterScenario
     */
    public function rollbackTransaction()
    {
        mysqli_query($this->mysqli, 'ROLLBACK;');
    }
}
