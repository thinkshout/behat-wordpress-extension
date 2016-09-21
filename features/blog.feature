Feature: You can read blog posts
    In order to read blogs
    As a user
    I need to go to the blog

    Background:
        Given I have a WordPress site
        And I have these plugins:
            | plugin     | status  |
            | buddypress | enabled |

    Scenario: List my blog posts
        Given I am on the homepage
        Then I should see "Hello world!"
