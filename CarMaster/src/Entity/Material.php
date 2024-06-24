<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping\{Column, Entity};

#[Entity]
class Material extends Product
{

    public function getType(): string
    {
        return 'material';
    }
}