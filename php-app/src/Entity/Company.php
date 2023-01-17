<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;

use App\Validator as CompanySymbolAssert;

class Company
{
    #[Assert\NotBlank]
    #[Assert\Regex('/[A-Z]/')]
    #[CompanySymbolAssert\CompanySymbol()]
    private ?string $CompanySymbol = null;

    #[Assert\NotBlank]
    #[Assert\LessThanOrEqual('today')]
    #[Assert\Expression(
        "this.getStartDate() <= this.getEndDate()",
        message: 'StartDate must be less or equal than EndDate',
    )]
    private ?\DateTimeInterface $StartDate = null;

    #[Assert\NotBlank]
    #[Assert\LessThanOrEqual('today')]
    #[Assert\Expression(
        "this.getEndDate() >= this.getStartDate()",
        message: 'EndDate must be greater or equal than StartDate',
    )]
    private ?\DateTimeInterface $EndDate = null;

    #[Assert\NotBlank]
    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    private ?string $email = null;


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
