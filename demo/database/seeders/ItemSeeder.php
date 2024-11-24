<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 101; $i <= 200; $i++) {
            Item::create([
                'name' => "Item $i",
                'description' => "This is the description for item $i."
            ]);
        }
    }
}
