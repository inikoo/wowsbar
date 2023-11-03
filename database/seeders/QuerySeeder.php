<?php

namespace Database\Seeders;

use App\Models\Helpers\Query;
use App\Models\Leads\Prospect;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class QuerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'slug' => Str::random(4),
                'name' => 'Prospects with email',
                'model_type' => class_basename(Prospect::class),
                'base' => json_encode(['with' => 'email']),
                'filters' => json_encode(['with' => 'phone'])
            ],
            [
                'slug' => Str::random(4),
                'name' => 'Prospects not contacted',
                'model_type' => class_basename(Prospect::class),
                'base' => json_encode(['without' => 'email']),
                'filters' => json_encode(['without' => 'phone'])
            ],
        ];

        foreach ($data as $item) {
            Query::create($item);
        }
    }
}
