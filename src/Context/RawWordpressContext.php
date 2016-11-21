<?php
namespace PaulGibbs\WordpressBehatExtension\Context;

use Behat\Behat\Context\SnippetAcceptingContext,
    Behat\MinkExtension\Context\RawMinkContext;

use PaulGibbs\WordpressBehatExtension\WordpressDriverManager;

/**
 * Base Behat Context class.
 *
 * Does not define any steps.
 */
class RawWordpressContext extends RawMinkContext implements WordpressAwareInterface, SnippetAcceptingContext
{
    /**
     * WordPress driver manager.
     *
     * @var WordpressDriverManager
     */
    protected $wordpress;

    /**
     * WordPress parameters.
     *
     * @var array
     */
    protected $wordpress_parameters;


    /**
     * Constructor.
     */
    public function __construct() {
    }

    /**
     * Set WordPress instance.
     *
     * @param WordpressDriverManager $wordpress
     */
    public function setWordpress(WordpressDriverManager $wordpress)
    {
        $this->wordpress = $wordpress;
    }

    /**
     * Get WordPress instance.
     *
     * @return WordpressDriverManager
     */
    public function getWordpress()
    {
        return $this->wordpress;
    }

    /**
     * Set parameters provided for WordPress.
     *
     * @param array $parameters
     */
    public function setWordpressParameters($parameters)
    {
        $this->wordpress_parameters = $parameters;
    }

    /**
     * Get a specific WordPress parameter.
     *
     * @param string $name Parameter name.
     * @return mixed
    */
    public function getWordpressParameter($name)
    {
        return isset($this->wordpress_parameters[$name]) ? $this->wordpress_parameters[$name] : null;
    }

    /**
     * Get active WordPress Driver.
     *
     * @param string $name Optional. Name of specific driver to retrieve.
     * @return PaulGibbs\WordpressBehatExtension\Driver\WordpressDriver
     */
    public function getDriver($name = '')
    {
        return $this->getWordpress()->getDriver($name);
    }

    /**
     * Wrap a closure in a spin check.
     *
     * This is a technique to accommodate in-progress state changes in a web page (i.e. waiting for new data to load)
     * by retrying the action for a given number of attempts, each delayed by 1 second. The closure is expected to
     * throw an exception should the expected state not (yet) exist.
     *
     * @param callable $closure Action to execute.
     * @param int      $tries Optional. Number of attempts to make before giving up.
     */
    public function spins(callable $closure, $tries = 10)
    {
        for ($i = 0; $i <= $tries; $i++) {
            try {
                call_user_func($closure);
                return;
            } catch (\Exception $e) {
                if ($i === $tries) {
                    throw $e;
                }
            }

            sleep(1);
        }
    }
}
