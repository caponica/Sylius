<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Behat\Page\Shop\Account;

use Sylius\Behat\Page\SymfonyPage;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
class LoginPage extends SymfonyPage implements LoginPageInterface
{
    /**
     * {@inheritdoc}
     */
    public function hasValidationErrorWith($message)
    {
        return $this->getElement('validation_error')->getText() === $message;
    }

    public function logIn()
    {
        $this->getDocument()->pressButton('Login');
    }

    /**
     * {@inheritdoc}
     */
    public function specifyPassword($password)
    {
        $this->getDocument()->fillField('Password', $password);
    }

    /**
     * {@inheritdoc}
     */
    public function specifyUserName($userName)
    {
        $this->getDocument()->fillField('Username', $userName);
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteName()
    {
        return 'sylius_shop_login';
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements()
    {
        return array_merge(parent::getDefinedElements(), [
            'validation_error' => '.message.error',
        ]);
    }
}
