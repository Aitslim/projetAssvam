<?php

namespace App\Security;

use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\User as AppUser;

class UserChecker implements UserCheckerInterface
{

    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }

        // if (!$user->isVerified()) {
        //     throw new CustomUserMessageAuthenticationException("Votre adresse Email n'a pas été vérifiée !");
        // }

        if ($user->IsSuspended()) {
            throw new CustomUserMessageAuthenticationException("Votre compte a été suspendu !");
        }

        // if ($user->getIsBanned()) {
        //     throw new CustomUserMessageAuthenticationException("You're banned !");
        // }
    }

    public function checkPostAuth(UserInterface $user)
    {
    }
}
