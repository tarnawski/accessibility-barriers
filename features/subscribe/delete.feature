Feature: Deactivate user alert
  In order to have possibility to hide alerts
  As a login user
  I need to be able to deactivate specific alert

  Background:
    Given There are the following subscribers:
      | ID | EMAIL                 | SECRET                               |
      | 1  | first@subscribe.pl    | 06811632-3e8e-435e-b57b-d4bfa2bd1398 |

  @cleanDB
  Scenario: Delete subscriber from list
    When I send a DELETE request to "/api/subscribers/06811632-3e8e-435e-b57b-d4bfa2bd1398"
    Then the response code should be 200
    And the JSON response should match:
    """
    {
      "status": "Success",
      "message": "Email properly removed"
    }
    """

