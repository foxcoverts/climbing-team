<?php

namespace Database\Seeders;

use App\Models\Key;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class KeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Key::factory()
            ->count(4)
            ->sequence(fn (Sequence $sequence) => [
                'name' => sprintf('Key %d', $sequence->index),
            ]);
    }
}
