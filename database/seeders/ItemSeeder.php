<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(0, 25) as $number) {
            Item::create([
                'name' => "リンゴ{$number}",
            ]);

            Item::create([
                'name' => "ぶどう{$number}",
            ]);

            Item::create([
                'name' => "みかん{$number}",
            ]);
        }

    }
}
