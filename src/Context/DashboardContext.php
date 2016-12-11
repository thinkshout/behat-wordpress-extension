<?php
namespace PaulGibbs\WordpressBehatExtension\Context;

/**
 * Provides step definitions that are specific to the WordPress dashboard.
 */
class DashboardContext extends RawWordpressContext
{
    /**
     * Check the specified notification is on-screen.
     *
     * Example: Then I should see a status message that says "Post published"
     *
     * @Then /^(?:I|they) should see an? (error|status) message that says "([^"]+)"$/
     *
     * @param string $type    Message type. Either "error" or "status".
     * @param string $message Text to search for.
     */
    public function iShouldSeeMessageThatSays($type, $message)
    {
        $selector = 'div.notice';

        if ($type === 'error') {
            $selector .= '.error';
        } else {
            $selector .= '.updated';
        }

        $this->assertSession()->elementTextContains('css', $selector, $message);
    }
}
