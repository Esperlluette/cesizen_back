<?php

namespace App\Controller\Admin;

use App\Entity\AppUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[AsController]
#[IsGranted('ROLE_ADMIN')]
final class SoftDeleteUserController
{
    public function __invoke(AppUser $user, EntityManagerInterface $em): JsonResponse
    {
        // Option simple : empêcher de se supprimer soi-même (retire si tu t'en fous)
        // if ($this->getUser() && $this->getUser()->getId() === $user->getId()) {
        //     return new JsonResponse(['error' => 'Cannot delete yourself'], 400);
        // }

        $user->setIsSuppressed(true);
        $user->setIsActive(false);
        $em->flush();

        return new JsonResponse(['id' => $user->getId(), 'deleted' => true], 200);
    }
}
