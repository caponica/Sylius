<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Sylius\Behat\NotificationType;
use Sylius\Behat\Page\Shop\Account\LoginPageInterface;
use Sylius\Behat\Page\Shop\Account\ResetPasswordPageInterface;
use Sylius\Behat\Page\Shop\HomePageInterface;
use Sylius\Behat\Service\NotificationCheckerInterface;
use Sylius\Behat\Service\Resolver\CurrentPageResolverInterface;
use Sylius\Component\Core\Test\Services\EmailCheckerInterface;
use Webmozart\Assert\Assert;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
class LoginContext implements Context
{
    /**
     * @var HomePageInterface
     */
    private $homePage;

    /**
     * @var LoginPageInterface
     */
    private $loginPage;

    /**
     * @var ResetPasswordPageInterface
     */
    private $resetPasswordPage;

    /**
     * @var CurrentPageResolverInterface
     */
    private $currentPageResolver;

    /**
     * @var EmailCheckerInterface
     */
    private $emailChecker;

    /**
     * @var NotificationCheckerInterface
     */
    private $notificationChecker;

    /**
     * @param HomePageInterface $homePage
     * @param LoginPageInterface $loginPage
     * @param ResetPasswordPageInterface $resetPasswordPage
     * @param CurrentPageResolverInterface $currentPageResolver
     * @param EmailCheckerInterface $emailChecker
     * @param NotificationCheckerInterface $notificationChecker
     */
    public function __construct(
        HomePageInterface $homePage,
        LoginPageInterface $loginPage,
        ResetPasswordPageInterface $resetPasswordPage,
        CurrentPageResolverInterface $currentPageResolver,
        EmailCheckerInterface $emailChecker,
        NotificationCheckerInterface $notificationChecker
    ) {
        $this->homePage = $homePage;
        $this->loginPage = $loginPage;
        $this->resetPasswordPage = $resetPasswordPage;
        $this->currentPageResolver = $currentPageResolver;
        $this->emailChecker = $emailChecker;
        $this->notificationChecker = $notificationChecker;
    }

    /**
     * @Given I want to log in
     */
    public function iWantToLogIn()
    {
        $this->loginPage->open();
    }

    /**
     * @Given I want to reset password
     */
    public function iWantToResetPassword()
    {
        $this->resetPasswordPage->open();
    }

    /**
     * @When I specify the user name as :userName
     * @When I do not specify the user name
     */
    public function iSpecifyTheUserName($userName = null)
    {
        $this->loginPage->specifyUserName($userName);
    }

    /**
     * @When I specify the email as :email
     * @When I do not specify the email
     */
    public function iSpecifyTheEmail($email = null)
    {
        $this->resetPasswordPage->specifyEmail($email);
    }

    /**
     * @When I specify the password as :password
     * @When I do not specify the password
     */
    public function iSpecifyThePasswordAs($password = null)
    {
        $this->loginPage->specifyPassword($password);
    }

    /**
     * @When I log in
     */
    public function iLogIn()
    {
        $this->loginPage->logIn();
    }

    /**
     * @When I reset it
     */
    public function iResetIt()
    {
        $this->resetPasswordPage->reset();
    }

    /**
     * @Then I should be logged in
     */
    public function iShouldBeLoggedIn()
    {
        Assert::true(
            $this->homePage->hasLogoutButton(),
            'I should be on home page and, also i should be able to sign out.'
        );
    }

    /**
     * @Then I should not be logged in
     */
    public function iShouldNotBeLoggedIn()
    {
        Assert::false(
            $this->homePage->hasLogoutButton(),
            'I should not be logged in.'
        );
    }

    /**
     * @Then I should be notified about bad credentials
     */
    public function iShouldBeNotifiedAboutBadCredentials()
    {
        Assert::true(
            $this->loginPage->hasValidationErrorWith('Error Invalid credentials.'),
            'I should see validation error.'
        );
    }

    /**
     * @Then I should be notified that email with reset instruction has been send
     */
    public function iShouldBeNotifiedThatEmailWithResetInstructionWasSend()
    {
        $this->notificationChecker->checkNotification('If the email you have specified exists in our system, we have sent there an instruction on how to reset your password.', NotificationType::success());
    }

    /**
     * @Then the email with reset token should be sent to :email
     */
    public function theEmailWithResetTokenShouldBeSentTo($email)
    {
        Assert::true(
            $this->emailChecker->hasRecipient($email),
            sprintf('Email should have been sent to %s.', $email)
        );
    }

    /**
     * @Then I should be notified that the :elementName is required
     */
    public function iShouldBeNotifiedThatElementIsRequired($elementName)
    {
        Assert::true(
            $this->resetPasswordPage->checkValidationMessageFor($elementName, sprintf('Please enter your %s.', $elementName)),
            sprintf('The %s should be required.', $elementName)
        );
    }
}
