<?php

namespace App\Controller\Admin;

use App\Entity\AppUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[AsController]
#[IsGranted('ROLE_ADMIN')]
final class ToggleUserStatusController
{
    public function __invoke(AppUser $user, Request $req, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($req->getContent(), true) ?? [];
        $active = $data['isActive'] ?? null;

        if (!is_bool($active)) {
            return new JsonResponse(['error' => 'isActive must be boolean'], 400);
        }

        $user->setIsActive($active);
        $em->flush();

        return new JsonResponse([
            'id' => $user->getId(),
            'isActive' => $user->isActive(),
        ], 200);
    }
}
