<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CompanySeeder::class,
            PaymentTermSeeder::class,
            CategorySeeder::class,
            BankAccountSeeder::class,
            CashRegisterSeeder::class,
            DocumentSequenceSeeder::class,
            ProductSeeder::class,
            CustomerSeeder::class,
            TaxSeeder::class,
            InvoiceSeeder::class,
        ]);
    }
}
