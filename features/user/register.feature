Feature: Register new user
  In order to have possibility register new user
  As a login user
  I need to be able to create account

  Background:
    Given There are the following clients:
      | ID | RANDOM_ID                                             | URL                                    | SECRET                                              | GRANT_TYPES                                                         |
      | 1  | 6035k9f52fc4gwskckowc8s0ws8swcco4ck0sk84owg4kg8kcg    | http://example.com,http://other.com    | 2vtd632tcku88cgssgwkk0o8o0gcs0o4ook8g0wc8gskgc8k8g  | authorization_code,client_credentials,refresh_token,password,token  |

  @cleanDB
  Scenario: Register new user
    When I send a POST request to "/api/register" with body:
    """
    {
      "first_name": "Adam",
      "last_name": "Naowak",
      "username": "anowak",
      "email": "user@nowak.eu",
      "password": "user",
      "client_id": "1_6035k9f52fc4gwskckowc8s0ws8swcco4ck0sk84owg4kg8kcg",
      "client_secret":"2vtd632tcku88cgssgwkk0o8o0gcs0o4ook8g0wc8gskgc8k8g"
    }
    """
    Then the response code should be 200
    And the JSON response should match:
    """
    {
      "access_token": @string@,
      "expires_in": 3600,
      "token_type": @string@,
      "scope": @null@,
      "refresh_token": @string@
    }
    """
