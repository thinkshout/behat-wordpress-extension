<?php
namespace PaulGibbs\WordpressBehatExtension\Context;

use Behat\Behat\Context\Context;

use PaulGibbs\WordpressBehatExtension\WordpressDriverManager;

/**
 * An interface for a WordPress object.
 */
interface WordpressAwareInterface extends Context
{
    /**
     * Set WordPress instance.
     *
     * @param WordpressDriverManager $wordpress
     */
    public function setWordpress(WordpressDriverManager $wordpress);

    /**
     * Get WordPress instance.
     *
     * @return WordpressDriverManager
     */
    public function getWordpress();

    /**
     * Sets parameters provided for WordPress.
     *
     * @param array $parameters
     */
    public function setWordpressParameters(array $parameters);

    /**
     * Get a specific WordPress parameter.
     *
     * @param string $name Parameter name.
     * @return mixed
     */
    public function getWordpressParameter($name);
}
