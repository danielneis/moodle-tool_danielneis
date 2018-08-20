@tool @tool_danielneis
Feature: All features as admin
  As an admin I should be able to access all features

  Background:
    Given the following "courses" exist:
      | fullname | shortname | category | groupmode |
      | Course 1 | C1        | 0        | 1         |

  Scenario: Access the tool as admin
    When I log in as "admin"
    And I am on "Course 1" course homepage
    And I navigate to "Tool example" in current page administration
    Then I should see "Tool example by Daniel"
    And I log out
