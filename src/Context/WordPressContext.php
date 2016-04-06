<?php

use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
namespace PaulGibbs\WordpressBehatExtension\Context;

use Behat\MinkExtension\Context\MinkContext;

//http://www.tentacode.net/10-tips-with-behat-and-mink
/*abstract whatever extends MinkContext {
public function __call($method, $parameters)
class WordpressContext extends MinkContext
{
    // we try to call the method on the Page first
    $page = $this->getSession()->getPage();
    if (method_exists($page, $method)) {
        return call_user_func_array(array($page, $method), $parameters);
    }

    // we try to call the method on the Session
    $session = $this->getSession();
    if (method_exists($session, $method)) {
        return call_user_func_array(array($session, $method), $parameters);
    }

    // could not find the method at all
    throw new \RuntimeException(sprintf(
        'The "%s()" method does not exist.', $method
    ));
}
}*/

{
    /**
     * Create a new WordPress website from scratch
     *
     * @Given /^\w+ have|has a vanilla wordpress installation$/
     */
    public function installWordPress(TableNode $table = null)
    {
        $name     = "admin";
        $email    = "an@example.com";
        $password = "test";
        $username = "admin";

        if ($table) {
            $hash     = $table->getHash();
            $row      = $hash[0];
            $name     = $row["name"];
            $username = $row["username"];
            $email    = $row["email"];
            $password = $row["password"];
        }

        $mysqli = new \Mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $value  = $mysqli->multi_query(implode("\n", array(
            "DROP DATABASE IF EXISTS " . DB_NAME . ";",
            "CREATE DATABASE " . DB_NAME . ";",
        )));
        assertTrue($value);

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        wp_install($name, $username, $email, true, '', $password);
    }
}
