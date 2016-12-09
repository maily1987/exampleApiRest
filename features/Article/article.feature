@article
Feature: I can manage articles

  Scenario: As regular article, I can list articles
    When I list articles
    Then I should get a valid list of 3 articles

  Scenario: As regular article, I can get valid article
    When I get article 1
    Then I should get valid article 1 data
