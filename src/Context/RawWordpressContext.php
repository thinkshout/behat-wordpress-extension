<?php
namespace PaulGibbs\WordpressBehatExtension\Context;

use Behat\Behat\Context\SnippetAcceptingContext,
    Behat\MinkExtension\Context\RawMinkContext;

use PaulGibbs\WordpressBehatExtension\WordpressDriverManager;

/**
 * Base class for WordPress Behat extensions. Does not define any steps.
 */
class RawWordpressContext extends RawMinkContext implements WordpressAwareInterface, SnippetAcceptingContext
{
    /**
     * WordPress driver manager.
     *
     * @var WordpressDriverManager
     */
    private $wordpress;

    /**
     * WordPress parameters.
     *
     * @var array
     */
    protected $wordpressParameters;


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
    public function setWordpressParameters(array $parameters)
    {
        $this->wordpressParameters = $parameters;
    }

    /**
     * Get a specific WordPress parameter.
     *
     * @param string $name Parameter name.
     * @return mixed
    */
    public function getWordpressParameter($name)
    {
        return isset($this->wordpressParameters[$name]) ? $this->wordpressParameters[$name] : null;
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
}
