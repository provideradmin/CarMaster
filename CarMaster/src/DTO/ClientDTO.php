<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ClientDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public string $name,

        #[Assert\NotBlank]
        #[Assert\Email]
        public string $email,

        #[Assert\NotBlank]
        #[Assert\Length(min: 9, max: 20)]
        public string $phone)
    {
    }
}
