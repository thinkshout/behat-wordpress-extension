<?php
namespace PaulGibbs\WordpressBehatExtension;

use PaulGibbs\WordpressBehatExtension\Driver\DriverInterface;

/**
 * Driver manager.
 */
class WordpressDriverManager
{
    /**
     * The name of the default driver.
     *
     * @var string $default_driver
     */
    protected $default_driver = 'wpcli';

    /**
     * All registered drivers.
     *
     * @var DriverInterface[]
     */
    protected $drivers = array();


    /**
     * Initialise the driver manager.
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
        $name   = strtolower($name) ?: $this->default_driver;
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

    /**
     * Set the default driver name.
     *
     * @param string $name Default driver name to set.
     */
    public function setDefaultDriverName($name)
    {
        $name = strtolower($name);

        if (! isset($this->drivers[$name])) {
            throw new \InvalidArgumentException(sprintf('Driver "%s" is not registered.', $name));
        }

        $this->default_driver = $name;
    }
}
