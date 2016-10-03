Feature: Create comment
  In order to have actual list of comment
  As a login user
  I need to be able to create new comment

  Background:
    Given There are the following clients:
      | ID | RANDOM_ID                                             | URL                                    | SECRET                                              | GRANT_TYPES                                                         |
      | 1  | 6035k9f52fc4gwskckowc8s0ws8swcco4ck0sk84owg4kg8kcg    | http://example.com,http://other.com    | 2vtd632tcku88cgssgwkk0o8o0gcs0o4ook8g0wc8gskgc8k8g  | authorization_code,client_credentials,refresh_token,password,token  |
    And there are the following users:
      | FIRST_NAME | LAST_NAME | USERNAME    | PASSWORD          | EMAIL                 | SUPERADMIN      | ENABLED | ROLE       |
      | Adam       | Nowak     | admin       | admin             | admin@admin.com       | true            | true    | ROLE_ADMIN |
    And There are the following access tokens:
      | ID | CLIENT | USER | TOKEN                                                                                  | EXPIRES_AT |
      | 1  | 1      | 1    | OWJkOGQzODliYTZjNTk3YTM1MmY0OTY2NjRlYTk2YmRmM2ZhNGE5YmZmMWVlYTg4MTllMmMxMzg3NzA4NGU5Nw | +2 days    |
    Given There are the following categories:
      | ID | NAME              |
      | 1  | Category number 1 |
      | 2  | Category number 2 |
    Given There are the following notifications:
      | ID | NAME           | DESCRIPTION                 | LATITUDE  | LONGITUDE | RATING | CREATED_AT | CATEGORY_ID | USER_ID |
      | 1  | Example name 1 | Example short description 1 | 50.033723 | 22.003051 | 0      | -5 day     | 1           | 1       |

  @cleanDB
  Scenario: Create new comment
    Given I set header "Authorization" with value "Bearer OWJkOGQzODliYTZjNTk3YTM1MmY0OTY2NjRlYTk2YmRmM2ZhNGE5YmZmMWVlYTg4MTllMmMxMzg3NzA4NGU5Nw"
    When I send a POST request to "/api/notifications/1/comments" with body:
    """
    {
        "content": "New comment"
    }
    """
    Then the response code should be 201
    And the JSON response should match:
    """
    {
      "id": @integer@,
      "content": "@string@",
      "created_at": "@string@"
    }
    """

  @cleanDB
  Scenario: Create new comment - not logged user
    When I send a POST request to "/api/notifications/1/comments" with body:
    """
    {
        "content": "New comment"
    }
    """
    Then the response code should be 401
    And the JSON response should match:
    """
    {
      "error": "access_denied",
      "error_description": "OAuth2 authentication required"
    }
    """