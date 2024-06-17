<?php
declare(strict_types=1);

namespace App\Manager;

use Symfony\Component\Serializer\Serializer;

class MySerializeManager
{
    private Serializer $serializer;

    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    public function serializeToJson(mixed $data): string
    {
        return $this->serializer->serialize($data, 'json');
    }
}
