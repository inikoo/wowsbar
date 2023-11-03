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
                'constrains' => [
                    'and' => [

                            'with' => 'email'

                    ]
                ],


            ],
            [
                'slug'       => 'prospects-not-contacted',
                'name'       => 'Prospects not contacted',
                'model_type' => class_basename(Prospect::class),
                'constrains' => [
                    'and' => [

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
            $queryData['is_seeded'] = true;
            if ($query = Query::where('slug', $queryData['slug'])->where('is_seeded', true)->first()) {
                UpdateQuery::run($query, $queryData);
            } else {
                StoreQuery::run($queryData);
            }
        }
    }
}
