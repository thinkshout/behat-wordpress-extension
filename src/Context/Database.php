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
        )) {
            die('MySQL connect error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
        }

        mysqli_multi_query(
            self::$mysqli,
            'SET GLOBAL TRANSACTION ISOLATION LEVEL READ UNCOMMITTED'
        );
    }

    /**
     * Close MySQL connection.
     */
    public static function terminateDatabaseConnection()
    {
        mysqli_multi_query(
            self::$mysqli,
            'SET GLOBAL TRANSACTION ISOLATION LEVEL REPEATABLE READ UNCOMMITTED'
        );
    }
    /**
     * Begin a database transaction.
     *
     * @BeforeScenario
     */
    public static function beginTransaction()
    {
    }

    /**
     * Roll it back after the scenario.
     *
     * @AfterScenario
     */
    public static function rollback()
    {
    }
}
