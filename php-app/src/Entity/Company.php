<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use App\Validator as CompanySymbolAssert;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Regex('/[A-Z]/')]
    #[CompanySymbolAssert\CompanySymbol()]
    private ?string $CompanySymbol = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\LessThanOrEqual('today')]
    #[Assert\Expression(
        "this.getStartDate() <= this.getEndDate()",
        message: 'StartDate must be less or equal than EndDate',
    )]
    private ?\DateTimeInterface $StartDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\LessThanOrEqual('today')]
    #[Assert\Expression(
        "this.getEndDate() >= this.getStartDate()",
        message: 'EndDate must be greater or equal than StartDate',
    )]
    private ?\DateTimeInterface $EndDate = null;

    #[ORM\Column(length: 255)]
    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    private ?string $email = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompanySymbol(): ?string
    {
        return $this->CompanySymbol;
    }

    public function setCompanySymbol(string $CompanySymbol): self
    {
        $this->CompanySymbol = $CompanySymbol;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->StartDate;
    }

    public function setStartDate(\DateTimeInterface $StartDate): self
    {
        $this->StartDate = $StartDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->EndDate;
    }

    public function setEndDate(\DateTimeInterface $EndDate): self
    {
        $this->EndDate = $EndDate;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
