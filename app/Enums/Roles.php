<?php

namespace App\Enums;

enum Roles : int {

    case CLIENT = 1;
    case RESTAURANT = 2;
    case RIDER = 3;
    case ADMIN = 4;

     public static function fromName(string $name): self
    {
        return constant(self::class . '::' . $name);
    }
    public function description(): string
    {
        return match ($this) {
            self::CLIENT => 'Client',
            self::RESTAURANT => 'Restaurant',
            self::RIDER => 'Rider',
            self::ADMIN => 'Administrator',
        };
    }

}