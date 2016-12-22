# @Then I/they should see a/an (error|status) message that says ":message"

Feature: Product basket
  In order to buy products
  As a customer
  I need to be able to put interesting products into a basket

  Scenario: Buying a single product under £10
    Given I am logged in as an admin
    When I add the "Sith Lord Lightsaber" to the basket
    Then I should see an error message that says "yolo"
