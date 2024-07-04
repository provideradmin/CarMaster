<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ClientDTO
{
    #[Assert\NotBlank]
    public string $name;

    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email;

    #[Assert\NotBlank]
    #[Assert\Length(min: 10, max: 20)]
    public string $phone;
}
