<?php
namespace PaulGibbs\WordpressBehatExtension\Context;

/**
 * Provides step definitions for creating posts, comments, and terms; managing plugins, themes, and
 * the cache; the database, and much more.
 */
class WordpressContext extends RawWordpressContext
{
    /**
     * Open the dashboard.
     *
     * Example: Given I am on the dashboard
     * Example: Given I am in wp-admin
     * Example: When I go to the dashboard
     * Example: When I go to wp-admin
     *
     * @Given /^(?:I am|they are) on the dashboard/
     * @Given /^(?:I am|they are) in wp-admin/
     * @When /^(?:I|they) go to the dashboard/
     * @When /^(?:I|they) go to wp-admin/
     */
    public function iAmOnDashboard()
    {
        $this->visitPath('wp-admin/');
    }
}
