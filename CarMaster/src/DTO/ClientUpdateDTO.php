<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ClientUpdateDTO
{
    public function __construct(
        #[Assert\Length(max: 255)]
        public ?string $name  = null,

        #[Assert\Email]
        public ?string $email = null,

        #[Assert\Length(min: 9, max: 20)]
        public ?string $phone = null)
    {
    }
}