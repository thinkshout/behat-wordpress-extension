<?php
namespace PaulGibbs\WordpressBehatExtension\Context;

trait Database
{
    /**
     * MySQL connection handle.
     *
     * @var mysqli_init
     */
    public static $mysqli = null;

    /**
     * Connect to MySQL.
     */
    public static function initializeDatabaseConnection()
    {
        self::$mysqli = mysqli_init();
        $db_settings  = $this->getParameters()['wordpress'];

        if (! @mysqli_real_connect(
            self::$mysqli,
            $db_settings['db_host'],
            $db_settings['db_username'],
            $db_settings['db_password'],
            $db_settings['db_name']
        ))
        {
            die('MySQL connect error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
        }

        mysqli_multi_query(
            self::$mysqli,
            'SET GLOBAL TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;'
        );
    }

    /**
     * Close MySQL connection.
     */
    public static function terminateDatabaseConnection()
    {
        mysqli_query(self::$mysqli, 'SET GLOBAL TRANSACTION ISOLATION LEVEL REPEATABLE READ;');
    }

    /**
     * Begin a database transaction before the scenario is run.
     *
     * @BeforeScenario
     */
    public static function startTransaction()
    {
        mysqli_query(self::$mysqli, 'START TRANSACTION;');
    }

    /**
     * Roll back database transaction after the scenario runs.
     *
     * @AfterScenario
     */
    public static function rollbackTransaction()
    {
        mysqli_query(self::$mysqli, 'ROLLBACK;');
    }
}
