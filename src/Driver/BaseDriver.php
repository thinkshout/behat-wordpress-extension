<?php
namespace PaulGibbs\WordpressBehatExtension\Driver;

use PaulGibbs\WordpressBehatExtension\Exception\UnsupportedDriverActionException;

/**
 * Common base class for WordPress drivers.
 *
 * A Driver represents and manages the connection between the Behat environment and a WordPress site.
 */
abstract class BaseDriver implements DriverInterface
{
}
