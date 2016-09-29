Feature: Show category
  In order to have possibility to show categories
  As a user
  I need to be able to show categories stored in database

  Background:
    Given There are the following categories:
      | ID | NAME              |
      | 1  | Category number 1 |
      | 1  | Category number 2 |

  @cleanDB
  Scenario: Get all categories
    When I send a GET request to "/api/categories"
    Then the response code should be 200
    And the JSON response should match:
    """
    [
      {
        "id": @integer@,
        "name": "@string@"
      },
      {
        "id": @integer@,
        "name": "@string@"
      }
    ]
    """

  @cleanDB
  Scenario: Get single category
    When I send a GET request to "/api/categories/1"
    Then the response code should be 200
    And the JSON response should match:
    """
    {
      "id": @integer@,
      "name": "@string@",
      "notifications": @array@
    }
    """