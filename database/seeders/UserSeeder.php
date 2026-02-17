<?php

namespace Database\Seeders;

use App\Enums\Roles;
use App\Modules\User\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Cria usuário padrão de teste com ID explícito
        User::query()->create([
            'id' => Str::uuid()->toString(),
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'role' => Roles::ADMIN->value,
        ]);

        // Cria usuários adicionais padrão (cliente) via factory
        User::factory(3)->create();
    }
}
