<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(0, 25) as $number) {
            Blog::create([
                'name' => "リンゴ{$number}",
                'content' => "リンゴ{$number}です",
            ]);

            Blog::create([
                'name' => "ぶどう{$number}",
                'content' => "ぶどう{$number}です",
            ]);

            Blog::create([
                'name' => "みかん{$number}",
                'content' => "みかん{$number}です",
            ]);
        }

    }
}
