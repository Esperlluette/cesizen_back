<?php

namespace App\Controller;

use App\Entity\AppUser;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[AsController]
final class GetMeController extends AbstractController
{
    public function __invoke(#[CurrentUser] ?AppUser $user): JsonResponse
    {
        if (!$user) {
            throw new AccessDeniedHttpException('Not authenticated.');
        }
        return $this->json($user, 200, [], ['groups' => ['user:read']]);
    }
}
