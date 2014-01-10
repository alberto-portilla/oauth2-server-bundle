Feature: OAuth2ServerBundle

  Scenario: Using alone
    Given I have a symfony kernel
    And I use the bundle "Akamon\OAuth2ServerBundle\AkamonOAuth2ServerBundle"
    When I boot the symfony kernel
    Then the symfony kernel should be booted

  Scenario: Using with the SymfonyFrameworkBundle
    Given I have a symfony kernel
    And I use the bundle "Symfony\Bundle\FrameworkBundle\FrameworkBundle"
    And I use the bundle "Akamon\OAuth2ServerBundle\AkamonOAuth2ServerBundle"
    And I have the kernel yaml config:
      """
      framework:
        secret: foo
      """
    When I boot the symfony kernel
    Then the symfony kernel should be booted
