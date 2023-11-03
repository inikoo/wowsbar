<?php

namespace Database\Seeders;

use App\Actions\Helpers\Query\StoreQuery;
use App\Actions\Helpers\Query\UpdateQuery;
use App\Enums\CRM\Prospect\ProspectStateEnum;
use App\Models\Helpers\Query;
use App\Models\Leads\Prospect;
use Illuminate\Database\Seeder;

class QuerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'slug'       => 'prospects-email',
                'name'       => 'Prospects with email',
                'model_type' => class_basename(Prospect::class),
                'base'       => [
                    [
                        'with' => 'email'
                    ]
                ],
                'filters'    => []

            ],
            [
                'slug'       => 'prospects-not-contacted',
                'name'       => 'Prospects not contacted',
                'model_type' => class_basename(Prospect::class),
                'base'       => [],
                'filters'    => [
                    [
                        'with'  => 'email',
                        'where' => [
                            'state',
                            '=',
                            ProspectStateEnum::NO_CONTACTED->value
                        ]
                    ]
                ]
            ],
        ];

        foreach ($data as $queryData) {
            $queryData['read_only'] = true;
            if ($query = Query::where('slug', $queryData['slug'])->where('read_only', true)->first()) {
                UpdateQuery::run($query, $queryData);
            } else {
                StoreQuery::run($queryData);
            }
        }
    }
}
