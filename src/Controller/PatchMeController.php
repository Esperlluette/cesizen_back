<?php

namespace App\Controller;

use App\Entity\AppUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[AsController]
final class PatchMeController
{
    public function __invoke(
        #[CurrentUser] ?AppUser $user,
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {
        if (!$user) {
            throw new AccessDeniedHttpException('Not authenticated.');
        }

        $data = json_decode($request->getContent(), true) ?? [];

        // champs autorisés (simple et clair)
        if (array_key_exists('email', $data) && is_string($data['email'])) {
            $user->setEmail(trim($data['email']));
        }
        if (array_key_exists('username', $data) && is_string($data['username'])) {
            $user->setUsername(trim($data['username']));
        }

        $em->flush();

        // renvoie l’utilisateur avec le groupe de lecture
        return new JsonResponse(
            // laisse API Platform sérialiser via normalizer si tu préfères,
            // ici on fait simple :
            [
                'email' => $user->getEmail(),
                'username' => $user->getUsername(),
            ],
            200
        );
    }
}
