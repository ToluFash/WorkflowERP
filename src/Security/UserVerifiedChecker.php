<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVerifiedChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        /**
         * @var $user User
         */

        if (!$user->isVerified()) {
            throw new CustomUserMessageAccountStatusException('Your user account is disabled, please contact the administrator.');
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        // TODO: Implement checkPostAuth() method.
    }


}