@email_verification
Feature: Verifying account's email address
    In order to improve security of my account
    As a User
    I want to be able to verify my email address

    Background:
        Given the store operates on a single channel in "France"
        And there is a "valkyrie@cain.com" user
        And this user is not verified

    @ui
    Scenario: Getting verified after clicking the link in the verification message
        Given a verification email has already been sent to "valkyrie@cain.com"
        When I try to verify my account using the link from this email
        Then I should be notified that the verification was successful
        And my account should be verified

    @ui
    Scenario: Being unable to verify with invalid token
        When I try to verify using "twinklelittlestar" token
        Then I should be notified that the verification token is invalid

    @ui
    Scenario: Resending the verification email as a logged in user
        Given I am logged in as "valkyrie@cain.com"
        When I resend the verification email
        Then I should be notified that the verification email has been sent
        And the email should be sent to "valkyrie@cain.com"

    @ui
    Scenario: Being unable to verify using old verification links
        Given I am logged in as "valkyrie@cain.com"
        And I have already received a verification email
        But I have not verified my account yet
        When I resend the verification email
        But I use the verification link from the first email to verify
        Then I should be notified that the verification token is invalid
        And my account should not be verified

    @ui
    Scenario: Being unable to resend verification token when verified
        Given I am logged in as "valkyrie@cain.com"
        And I have already verified my account
        Then I should be unable to resend the verification email

    @ui
    Scenario: Receiving account verification email after registration
        When I register with email "ghastly@bespoke.com" and password "suitsarelife"
        Then I should be logged in
        And I should be notified that my account has been created and the verification email has been sent
        And the verification email should be sent to "ghastly@bespoke.com"
        But my account should not be verified
