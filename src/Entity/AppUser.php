<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\AppUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Get;
use Symfony\Component\Serializer\Annotation\SerializedName;
use App\Controller\GetMeController;
use ApiPlatform\Metadata\Patch;
use App\Controller\PatchMeController;
use App\Controller\PatchMePasswordController;
use App\Controller\Admin\ToggleUserStatusController;
use ApiPlatform\Metadata\Delete;
use App\Controller\Admin\SoftDeleteUserController;


#[ORM\Entity(repositoryClass: AppUserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
#[ApiResource(
    normalizationContext: ['groups' => ['user:read']],
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['user:read']]
        ),
        new Post(
            denormalizationContext: ['groups' => ['user:create']],
            normalizationContext: ['groups' => ['user:read']]
        ),
        new Get(
            normalizationContext: ['groups' => ['user:read']]
        ),
        new Get(
            uriTemplate: '/me',
            name: 'api_me',
            controller: GetMeController::class,
            read: false,
            security: "is_granted('IS_AUTHENTICATED_FULLY')"
        ),
        new Patch(
            uriTemplate: '/me',
            controller: PatchMeController::class,
            security: "is_granted('IS_AUTHENTICATED_FULLY')",
            read: false,
            write: false
        ),
        new Patch(
            uriTemplate: '/me/password',
            controller: PatchMePasswordController::class,
            security: "is_granted('IS_AUTHENTICATED_FULLY')",
            read: false,
            write: false
        ),
        new GetCollection(
            uriTemplate: '/admin/users',
            security: "is_granted('ROLE_ADMIN')",
            paginationEnabled: true
        ),
        new Get(
            uriTemplate: '/admin/users/{id}',
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Patch(
            uriTemplate: '/admin/users/{id}/status',
            controller: ToggleUserStatusController::class,
            security: "is_granted('ROLE_ADMIN')",
            read: true,   // charge l'entité par {id}
            write: false  // on gère le flush dans le contrôleur
        ),
        new Delete(
            uriTemplate: '/admin/users/{id}',
            controller: SoftDeleteUserController::class,
            security: "is_granted('ROLE_ADMIN')",
            read: true,   // charge l'entité par {id}
            write: false  // on flush dans le contrôleur
        ),

    ]
)]
class AppUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    #[Groups(['user:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Groups(['user:read', 'user:create'])]
    private ?string $username = null;

    /** @var list<string> */
    #[ORM\Column]
    #[Groups(['user:create', 'user:read'])]
    private array $roles = [];


    /** @var string */
    #[ORM\Column]
    private ?string $password = null;


    /** @var string */
    /** Mot de passe en clair (pas mappé, write-only) */
    #[Groups(['user:update', 'user:create'])]
    #[SerializedName('password')]
    private ?string $plainPassword = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:read', 'user:create'])]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:read', 'user:create'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:read', 'user:create'])]
    private ?string $first_name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:read', 'user:create'])]
    private ?string $country_from = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:read', 'user:create'])]
    private ?string $city = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['user:read', 'user:create'])]
    private ?\DateTimeInterface $birth_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['user:read'])]
    private ?\DateTimeInterface $acct_created_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(['user:read'])]
    private ?\DateTimeInterface $acct_last_update_date = null;

    #[ORM\Column]
    #[Groups(['user:read'])]
    private ?bool $is_active = null;

    #[ORM\Column]
    #[Groups(['user:read'])]
    private ?bool $is_suppressed = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:read'])]
    private ?string $recover_code = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(['user:read'])]
    private ?\DateTimeInterface $recovery_date = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[Groups(['user:read'])]
    private ?Profile $profile = null;

    /**
     * @var Collection<int, Page>
     */
    #[ORM\OneToMany(targetEntity: Page::class, mappedBy: 'created_by', orphanRemoval: true)]
    #[Groups(['user:read'])]
    private Collection $pages;


    #[ORM\OneToOne(mappedBy: 'updated_by', cascade: ['persist', 'remove'])]
    #[Groups(['user:read'])]
    private ?Page $pages_updated = null;
    public function __construct()
    {
        $this->pages = new ArrayCollection();
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }
    public function setPlainPassword(?string $p): void
    {
        $this->plainPassword = $p;
    }



    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getCountryFrom(): ?string
    {
        return $this->country_from;
    }

    public function setCountryFrom(string $country_from): static
    {
        $this->country_from = $country_from;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getBirthDate(): ?\DateTime
    {
        return $this->birth_date;
    }

    public function setBirthDate(\DateTime $birth_date): static
    {
        $this->birth_date = $birth_date;

        return $this;
    }

    public function getAcctCreatedDate(): ?\DateTime
    {
        return $this->acct_created_date;
    }

    public function setAcctCreatedDate(\DateTime $acct_created_date): static
    {
        $this->acct_created_date = $acct_created_date;

        return $this;
    }

    public function getAcctLastUpdateDate(): ?\DateTime
    {
        return $this->acct_last_update_date;
    }

    public function setAcctLastUpdateDate(\DateTime $acct_last_update_date): static
    {
        $this->acct_last_update_date = $acct_last_update_date;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): static
    {
        $this->is_active = $is_active;

        return $this;
    }

    public function isSuppressed(): ?bool
    {
        return $this->is_suppressed;
    }

    public function setIsSuppressed(bool $is_suppressed): static
    {
        $this->is_suppressed = $is_suppressed;

        return $this;
    }

    public function getRecoverCode(): ?string
    {
        return $this->recover_code;
    }

    public function setRecoverCode(string $recover_code): static
    {
        $this->recover_code = $recover_code;

        return $this;
    }

    public function getRecoveryDate(): ?\DateTime
    {
        return $this->recovery_date;
    }

    public function setRecoveryDate(?\DateTime $recovery_date): static
    {
        $this->recovery_date = $recovery_date;

        return $this;
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(?Profile $profile): static
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * @return Collection<int, Page>
     */
    public function getPages(): Collection
    {
        return $this->pages;
    }

    public function addPage(Page $page): static
    {
        if (!$this->pages->contains($page)) {
            $this->pages->add($page);
            $page->setCreatedBy($this);
        }

        return $this;
    }

    public function removePage(Page $page): static
    {
        if ($this->pages->removeElement($page)) {
            // set the owning side to null (unless already changed)
            if ($page->getCreatedBy() === $this) {
                $page->setCreatedBy(null);
            }
        }

        return $this;
    }

    public function getPagesUpdated(): ?Page
    {
        return $this->pages_updated;
    }

    public function setPagesUpdated(?Page $pages_updated): static
    {
        // unset the owning side of the relation if necessary
        if ($pages_updated === null && $this->pages_updated !== null) {
            $this->pages_updated->setUpdatedBy(null);
        }

        // set the owning side of the relation if necessary
        if ($pages_updated !== null && $pages_updated->getUpdatedBy() !== $this) {
            $pages_updated->setUpdatedBy($this);
        }

        $this->pages_updated = $pages_updated;

        return $this;
    }
}
