<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            [
                'name' => 'Clarissa Stephanie',
                'phone' => '081298761234',
                'email' => 'customer@blushboutique.com',
                'address' => 'Jl. Dago No. 18, Bandung, Jawa Barat',
            ],
            [
                'name' => 'Nadhira Az-Zahra',
                'phone' => '081387659876',
                'email' => 'nadhira.azzahra@yahoo.com',
                'address' => 'Komplek Buah Batu Indah Blok C-4, Bandung, Jawa Barat',
            ],
            [
                'name' => 'Aurelia Michelle',
                'phone' => '082134568899',
                'email' => 'aurelia.michelle@gmail.com',
                'address' => 'Jl. Gandapura No. 5A, Riau, Bandung',
            ],
            [
                'name' => 'Felicia Anggraini',
                'phone' => '085712345678',
                'email' => 'felicia.ang@hotmail.com',
                'address' => 'Jl. Setiabudi Regency No. 88, Bandung',
            ],
            [
                'name' => 'Sheila Amanda',
                'phone' => '081987654321',
                'email' => 'sheila.amanda@outlook.com',
                'address' => 'Jl. R.E. Martadinata No. 45, Bandung',
            ],
        ];

        foreach ($customers as $data) {
            Customer::firstOrCreate(
                ['phone' => $data['phone']],
                $data
            );
        }
    }
}
