<?php

namespace App\Security;

use App\Entity\AppUser;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class AppUserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof AppUser) return;

        if ($user->isSuppressed()) {
            throw new CustomUserMessageAccountStatusException('Account deleted.');
        }
        if (!$user->isActive()) {
            throw new CustomUserMessageAccountStatusException('Account disabled.');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        // rien
    }
}
