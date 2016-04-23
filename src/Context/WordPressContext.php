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
     * Connect to MySQL.
     */
    public function initializeDatabaseConnection()
    {
        $this->mysqli = mysqli_init();
        $db_settings  = $this->contextInitializer->getParameters()['wordpress'];

        if (! @mysqli_real_connect(
            $this->mysqli,
            $db_settings['db_host'],
            $db_settings['db_username'],
            $db_settings['db_password'],
            $db_settings['db_name']
        ))
        {
            die('MySQL connect error: (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
        }

        die(var_dump('djpaul one'));
        mysqli_multi_query(
            $this->mysqli,
            'SET GLOBAL TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;'
        );
    }

    /**
     * Close MySQL connection.
     */
    public function terminateDatabaseConnection()
    {
        die(var_dump('djpaul four'));
        mysqli_query($this->mysqli, 'SET GLOBAL TRANSACTION ISOLATION LEVEL REPEATABLE READ;');
    }

    /**
     * Begin a database transaction before the scenario is run.
     *
     * @BeforeScenario
     */
    public function startTransaction()
    {
        die(var_dump('djpaul two'));
        mysqli_query($this->mysqli, 'START TRANSACTION;');
    }

    /**
     * Roll back database transaction after the scenario runs.
     *
     * @AfterScenario
     */
    public function rollbackTransaction()
    {
        die(var_dump('djpaul threetw'));
        mysqli_query($this->mysqli, 'ROLLBACK;');
    }
}
