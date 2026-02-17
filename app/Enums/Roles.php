<?php

namespace App\Enums;

enum Roles : int {
    case ADMIN = 1;
    case USER = 2;
    case CLIENT = 3;


     public static function fromName(string $name): self
    {
        return constant(self::class . '::' . $name);
    }
    public function description(): string
    {
        return match ($this) {
            self::USER => 'User',
            self::CLIENT => 'Client',
            self::ADMIN => 'Administrator',
        };
    }

}