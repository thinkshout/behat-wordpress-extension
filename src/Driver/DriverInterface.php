<?php
namespace PaulGibbs\WordpressBehatExtension\Driver;

/**
 * Interface for a WordPress Driver.
 *
 * A Driver represents and manages a connection between the Behat environment and a WordPress site.
 */
interface DriverInterface {
    /**
     * Bootstraps operations, as needed.
     */
    public function bootstrap();

    /**
     * Determines if the driver has been bootstrapped.
     */
   public function isBootstrapped();
}
