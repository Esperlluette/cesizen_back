<?php

namespace App\Controller;

use App\Entity\AppUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[AsController]
final class PatchMePasswordController
{
    public function __invoke(
        #[CurrentUser] ?AppUser $user,
        Request $request,
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $em
    ): JsonResponse {
        if (!$user) {
            throw new AccessDeniedHttpException('Not authenticated.');
        }

        $data = json_decode($request->getContent(), true) ?? [];
        $old = $data['oldPassword'] ?? null;
        $new = $data['newPassword'] ?? null;

        if (!is_string($old) || !is_string($new) || $new === '') {
            return new JsonResponse(['error' => 'Invalid payload'], 400);
        }
        if (!$hasher->isPasswordValid($user, $old)) {
            return new JsonResponse(['error' => 'Old password invalid'], 400);
        }
        if (strlen($new) < 8) { // règle simple
            return new JsonResponse(['error' => 'New password too short'], 400);
        }

        $user->setPassword($hasher->hashPassword($user, $new));
        $em->flush();

        return new JsonResponse(['message' => 'Password updated'], 200);
    }
}
