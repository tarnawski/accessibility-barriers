Feature: Show area
  In order to have possibility to show areas
  As a user
  I need to be able to show areas stored in database

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
    Given There are the following areas:
      | ID | NAME          | LATITUDE  | LONGITUDE | DISTANCE | USER_ID |
      | 1  | Area number 1 | 50.033723 | 22.003051 | 1        | 1       |
      | 2  | Area number 2 | 50.033131 | 21.998695 | 2        | 1       |

  @cleanDB
  Scenario: Get all areas
    Given I set header "Authorization" with value "Bearer OWJkOGQzODliYTZjNTk3YTM1MmY0OTY2NjRlYTk2YmRmM2ZhNGE5YmZmMWVlYTg4MTllMmMxMzg3NzA4NGU5Nw"
    When I send a GET request to "/api/user/1/areas"
    Then the response code should be 200
    And the JSON response should match:
    """
    [
      {
        "id": @integer@,
        "name": "@string@",
        "coordinates": {
          "latitude": "@string@",
          "longitude": "@string@"
        },
        "distance": "@integer@"
      },
      {
        "id": @integer@,
        "name": "@string@",
        "coordinates": {
          "latitude": "@string@",
          "longitude": "@string@"
        },
        "distance": "@integer@"
      }
    ]
    """

