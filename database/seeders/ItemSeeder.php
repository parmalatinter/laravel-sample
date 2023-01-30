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
        Item::create([
            'name' => 'リンゴ',
        ]);

        Item::create([
            'name' => 'ぶどう',
        ]);

        Item::create([
            'name' => 'みかん',
        ]);
    }
}
