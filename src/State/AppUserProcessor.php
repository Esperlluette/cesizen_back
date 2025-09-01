<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\AppUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class AppUserProcessor implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $decoratedPersistProcessor,
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $hasher
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if ($data instanceof AppUser) {
            $data->setAcctCreatedDate(new \DateTime());
            $data->setIsActive(true);
            $data->setIsSuppressed(false);
            $data->setRecoverCode('');

            // HASH si un mot de passe clair est fourni
            $plain = $data->getPlainPassword();
            if (!empty($plain)) {
                $data->setPassword($this->hasher->hashPassword($data, $plain));
                $data->eraseCredentials(); // null le plainPassword
            }
        }

        return $this->decoratedPersistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
