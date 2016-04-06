Feature: You can read blog posts
    In order to read blogs
    As a user
    I need to go to the blog

    Scenario: List my blog posts
        Given I am on the homepage
        Then I should see "Just my article"
        And I should see "Hello World"
        And I should not see "My draft"

    Scenario: Read a blog post
        Given I am on the homepage
        When I follow "Just my article"
        Then I should see "Just my article"
        And I should see "The content of my article"

