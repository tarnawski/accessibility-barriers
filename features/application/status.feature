Feature: Show status
  In order to have possibility to show status application
  As a user
  I need to be able to show status application

  Background:
    Given There are the following categories:
      | ID | NAME              |
      | 1  | Category number 1 |
      | 2  | Category number 2 |
      | 3  | Category number 3 |
    Given There are the following notifications:
      | ID | NAME           | DESCRIPTION                 | ADDRESS           | LATITUDE  | LONGITUDE | CREATED_AT | CATEGORY_ID | USER_ID |
      | 1  | Example name 1 | Example short description 1 | Example address 1 | 50.033723 | 22.003051 | -5 day     | 1           | 1       |
      | 2  | Example name 2 | Example short description 2 | Example address 2 | 50.033131 | 21.998695 | -8 day     | 2           | 2       |

  @cleanDB
  Scenario: Get status application
    When I send a GET request to "/api/status"
    Then the response code should be 200
    And the JSON response should match:
    """
    {
      "category": "3",
      "notifications": "2"
    }
    """
