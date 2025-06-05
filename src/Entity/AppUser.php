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


#[ORM\Entity(repositoryClass: AppUserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
#[ApiResource]
class AppUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $username = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $first_name = null;

    #[ORM\Column(length: 255)]
    private ?string $country_from = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $birth_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $acct_created_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $acct_last_update_date = null;

    #[ORM\Column]
    private ?bool $is_active = null;

    #[ORM\Column]
    private ?bool $is_suppressed = null;

    #[ORM\Column(length: 255)]
    private ?string $recover_code = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $recovery_date = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Profile $profile = null;

    /**
     * @var Collection<int, Page>
     */
    #[ORM\OneToMany(targetEntity: Page::class, mappedBy: 'created_by', orphanRemoval: true)]
    private Collection $pages;

    #[ORM\OneToOne(mappedBy: 'updated_by', cascade: ['persist', 'remove'])]
    private ?Page $pages_updated = null;

    public function __construct()
    {
        $this->pages = new ArrayCollection();
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

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
