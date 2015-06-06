Feature: PrintController
  In order to print shipment related documents
  As a developer
  I need PrintController to get dispatch confirmation / labels prints

  Background:
    Given there is vendor "my-vendor"
    And there is dispatch confirmation number "1234" for "my-vendor"

  Scenario: Download Dispatch Confirmation receipt
    When I request for download of Dispatch Confirmation receipt
    Then I should get Dispatch Confirmation receipt

  Scenario: View Dispatch Confirmation receipt
    When I request for view of Dispatch Confirmation receipt
    Then I should see Dispatch Confirmation receipt

  Scenario: Download Dispatch Confirmation labels
    When I request for download of Dispatch Confirmation labels
    Then I should get Dispatch Confirmation receipt

  Scenario: View Dispatch Confirmation labels
    When I request for view of Dispatch Confirmation labels
    Then I should see Dispatch Confirmation labels

  Scenario: Download Dispatch Confirmation labels
    Given there is consignment with ID "1234"
    When I request for view of Consignment label
    Then I should get Consignment label

  Scenario: View Dispatch Confirmation labels
    Given there is consignment with ID "1234"
    When I request for view of Consignment label
    Then I should see Consignment label
