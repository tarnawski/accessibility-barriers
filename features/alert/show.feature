Feature: Show alerts
  In order to have possibility to get user alerts
  As a login user
  I need to be able to get details account

  Background:
    Given There are the following clients:
      | ID | RANDOM_ID                                             | URL                                    | SECRET                                              | GRANT_TYPES                                                         |
      | 1  | 6035k9f52fc4gwskckowc8s0ws8swcco4ck0sk84owg4kg8kcg    | http://example.com,http://other.com    | 2vtd632tcku88cgssgwkk0o8o0gcs0o4ook8g0wc8gskgc8k8g  | authorization_code,client_credentials,refresh_token,password,token  |
    And there are the following users:
      | FIRST_NAME | LAST_NAME | USERNAME    | PASSWORD          | EMAIL                 | SUPERADMIN      | ENABLED | ROLE       |
      | Janusz     | Kowalski  | test        | test              | test@test.com         | false           | true    | ROLE_API   |
    And There are the following access tokens:
      | ID | CLIENT | USER | TOKEN                                                                                  | EXPIRES_AT |
      | 1  | 1      | 1    | OWJkOGQzODliYTZjNTk3YTM1MmY0OTY2NjRlYTk2YmRmM2ZhNGE5YmZmMWVlYTg4MTllMmMxMzg3NzA4NGU5Nw | +2 days    |
    Given There are the following categories:
      | ID | NAME              |
      | 1  | Category number 1 |
    Given There are the following notifications:
      | ID | NAME           | DESCRIPTION                 | ADDRESS           | LATITUDE  | LONGITUDE | CREATED_AT | CATEGORY_ID | USER_ID |
      | 1  | Example name 1 | Example short description 1 | Example address   | 50.033723 | 22.003051 | -5 day     | 1           | 1       |
    Given There are the following comments:
      | ID | CONTENT           | CREATED_AT | NOTIFICATION_ID | USER_ID |
      | 1  | Example content 1 |  -5 day    | 1               | 1       |
    Given There are the following alerts:
      | ID | ACTIVE | CREATED_AT | USER_ID | NOTIFICATION_ID | MESSAGE         |
      | 1  |  TRUE  |  -5 day    | 1       | 1               | Example message |
      | 2  |  TRUE  |  -6 day    | 1       | 1               | Example message |

  @cleanDB
  Scenario: Get active user alerts
    Given I set header "Authorization" with value "Bearer OWJkOGQzODliYTZjNTk3YTM1MmY0OTY2NjRlYTk2YmRmM2ZhNGE5YmZmMWVlYTg4MTllMmMxMzg3NzA4NGU5Nw"
    When I send a GET request to "/api/alerts"
    Then the response code should be 200
    And the JSON response should match:
    """
    [
      {
        "id": @integer@,
        "notification": {
          "id": @integer@,
          "name": "@string@",
          "address": "@string@"
        },
        "message": "@string@"
      },
      {
        "id": @integer@,
        "notification": {
          "id": @integer@,
          "name": "@string@",
          "address": "@string@"
        },
        "message": "@string@"
      }
    ]
    """

