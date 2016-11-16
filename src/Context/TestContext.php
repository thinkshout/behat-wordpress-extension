<?php
namespace PaulGibbs\WordpressBehatExtension\Context;

use Behat\Behat\Tester\Exception\PendingException;

class TestContext extends RawWordpressContext {
    /**
     * @BeforeSuite
     */
    public static function omgadebug()
    {
    }

    /**
     * @Given I am on cool
     */
    public function iAmOnCool()
    {
        $test = $this->getDriver()->switchTheme('hello-dolly');

        die(var_dump( $test ));
        throw new PendingException();
    }

    /**
     * @When I spurglefill in :arg1 with :arg2
     */
    public function iSpurglefillInWith($arg1, $arg2)
    {
        throw new PendingException();
    }
}
