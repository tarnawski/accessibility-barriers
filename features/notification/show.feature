Feature: Show notification
  In order to have possibility to show notification
  As a user
  I need to be able to show notification stored in database

  Background:
    Given There are the following clients:
      | ID | RANDOM_ID                                             | URL                                    | SECRET                                              | GRANT_TYPES                                                         |
      | 1  | 6035k9f52fc4gwskckowc8s0ws8swcco4ck0sk84owg4kg8kcg    | http://example.com,http://other.com    | 2vtd632tcku88cgssgwkk0o8o0gcs0o4ook8g0wc8gskgc8k8g  | authorization_code,client_credentials,refresh_token,password,token  |
    And there are the following users:
      | FIRST_NAME | LAST_NAME | USERNAME    | PASSWORD          | EMAIL                 | SUPERADMIN      | ENABLED | ROLE       |
      | Adam       | Nowak     | admin       | admin             | admin@admin.com       | true            | true    | ROLE_ADMIN |
      | Janusz     | Kowalski  | test        | test              | test@test.com         | false           | true    | ROLE_API   |
    And There are the following access tokens:
      | ID | CLIENT | USER | TOKEN                                                                                  | EXPIRES_AT |
      | 1  | 1      | 1    | OWJkOGQzODliYTZjNTk3YTM1MmY0OTY2NjRlYTk2YmRmM2ZhNGE5YmZmMWVlYTg4MTllMmMxMzg3NzA4NGU5Nw | +2 days    |
      | 2  | 1      | 2    | SDFSDFSDFSDFSDZjNTk3YTM1MmY0OTY2NjRlYTk2YmRmM2ZhNGE5YmZmMWVlYTg4MTllMmMxMDSFSDFSDFSDFm | +2 days    |
    Given There are the following categories:
      | ID | NAME              |
      | 1  | Category number 1 |
      | 2  | Category number 2 |
    Given There are the following notifications:
      | ID | NAME           | DESCRIPTION                 | LATITUDE  | LONGITUDE | CREATED_AT | CATEGORY_ID | USER_ID |
      | 1  | Example unique | Example short description 1 | 50.033723 | 22.003051 | -5 day     | 1           | 1       |
      | 2  | Example name 2 | Example short description 2 | 50.033131 | 21.998695 | -8 day     | 2           | 2       |

  @cleanDB
  Scenario: Get all notifications
    When I send a GET request to "/api/notifications"
    Then the response code should be 200
    And the JSON response should match:
    """
    [
      {
        "id": @integer@,
        "name": "@string@",
        "description": "@string@",
        "coordinates": {
          "latitude": "@string@",
          "longitude": "@string@"
        },
        "rating": {
          "average": @integer@,
          "count": @integer@
        },
        "created_at": "@string@"
      },
      {
        "id": @integer@,
        "name": "@string@",
        "description": "@string@",
        "coordinates": {
          "latitude": "@string@",
          "longitude": "@string@"
        },
        "rating": {
          "average": @integer@,
          "count": @integer@
        },
        "created_at": "@string@"
      }
    ]
    """

  @cleanDB
  Scenario: Get notifications by query
    When I send a GET request to "/api/notifications?name=unique"
    Then the response code should be 200
    And the JSON response should match:
    """
    [
      {
        "id": @integer@,
        "name": "Example unique",
        "description": "@string@",
        "coordinates": {
          "latitude": "@string@",
          "longitude": "@string@"
        },
        "rating": {
          "average": @integer@,
          "count": @integer@
        },
        "created_at": "@string@"
      }
    ]
    """

  @cleanDB
  Scenario: Get single notification
    When I send a GET request to "/api/notifications/1"
    Then the response code should be 200
    And the JSON response should match:
    """
      {
        "id": @integer@,
        "name": "@string@",
        "description": "@string@",
        "coordinates": {
          "latitude": "@string@",
          "longitude": "@string@"
        },
        "rating": {
          "average": @integer@,
          "count": @integer@
        },
        "created_at": "@string@",
        "user": {
          "first_name": "@string@",
          "last_name": "@string@"
        },
        "category": {
          "id": @integer@,
          "name": "@string@",
          "notification_count": @integer@
        },
        "comments": @array@
      }
    """