<?php
namespace PaulGibbs\WordpressBehatExtension;

use PaulGibbs\WordpressBehatExtension\Driver\DriverInterface;

/**
 * WordPress driver manager.
 */
class WordpressDriverManager
{
    /**
     * The name of the default driver.
     *
     * @var string $defaultDriver
     */
    const defaultDriver = 'wp-cli';

    /**
     * All registered drivers.
     *
     * @var DriverInterface[]
     */
    protected $drivers = array();


    /**
     * Initialize the driver manager.
     *
     * @param DriverInterface[] $drivers An array of drivers to register.
     */
    public function __construct(array $drivers = array())
    {
        foreach ($drivers as $name => $driver) {
            $this->registerDriver($name, $driver);
        }
    }

    /**
     * Register a new driver.
     *
     * @param string          $name Driver name.
     * @param DriverInterface $driver An instance of a DriverInterface.
     */
    public function registerDriver($name, DriverInterface $driver)
    {
        $name = strtolower($name);
        $this->drivers[$name] = $driver;
    }

    /**
     * Return a registered driver by name, or the default driver.
     *
     * @param string $name Optional. The name of the driver to return. If omitted, the default driver is returned.
     * @return DriverInterface The requested driver.
     */
    public function getDriver($name = '')
    {
        $name   = strtolower($name) ?: $this->defaultDriver;
        $driver = $this->drivers[$name];

        if (! $driver->isBootstrapped()) {
            $driver->bootstrap();
        }

        return $driver;
    }

    /**
     * Returns all registered drivers.
     *
     * @return DriverInterface[] An array of drivers.
     */
    public function getDrivers()
    {
       return $this->drivers;
    }
}
