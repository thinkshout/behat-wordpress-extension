Feature: Search
    In order to test something
    As a website user
    I need to be able to start a test

    @javascript
    Scenario: Searching for a page with autocompletion
        Given I am on "http://google.com"
        When I fill in "lst-ib" with "Behavior Driv"
        And I wait for the suggestion box to appear
        Then I should see "Behavior-driven development"
