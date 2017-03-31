Feature: Add subscribe
  In order to have possibility to add new email to subscribe list
  As a user
  I need to be able to add email

  @cleanDB
  Scenario: Add email
    When I send a POST request to "/api/subscribers" with body:
    """
    {
      "email": "first@subscribe.pl"
    }
    """
    Then the response code should be 201
    And the JSON response should match:
    """
    {
      "id": @integer@,
      "email": "first@subscribe.pl",
      "secret": "@string@"
    }
    """

