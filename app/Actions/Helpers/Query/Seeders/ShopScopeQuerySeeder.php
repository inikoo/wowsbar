<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 06 Nov 2023 10:49:43 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\Query\Seeders;

use App\Actions\Helpers\Query\Hydrators\QueryHydrateCount;
use App\Actions\Helpers\Query\StoreQuery;
use App\Actions\Helpers\Query\UpdateQuery;
use App\Actions\Traits\WithShopsArgument;
use App\Enums\CRM\Prospect\ProspectStateEnum;
use App\Models\Helpers\Query;
use App\Models\Leads\Prospect;
use App\Models\Market\Shop;
use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;

class ShopScopeQuerySeeder
{
    use AsAction;
    use WithShopsArgument;

    public function handle(Shop $shop): void
    {
        $data = [
            [
                'slug'       => 'prospects-not-contacted',
                'name'       => 'Prospects not contacted',
                'model_type' => class_basename(Prospect::class),
                'constrains' => [
                    [
                        'type'       => 'with',
                        'parameters' => 'email'
                    ],
                    [
                        'type'       => 'where',
                        'parameters' => [
                            'state',
                            '=',
                            ProspectStateEnum::NO_CONTACTED->value
                        ]
                    ]

                ]
            ],
            [
                'slug'       => 'prospects-last-contacted',
                'name'       => 'Prospects last contacted (within interval)',
                'model_type' => class_basename(Prospect::class),
                'constrains' => [

                    [
                        'type'       => 'with',
                        'parameters' => 'email'
                    ],

                    [
                        'type'       => 'group',
                        'parameters' => [
                            [
                                'type'       => 'where',
                                'parameters' => [
                                    'state',
                                    '=',
                                    ProspectStateEnum::NO_CONTACTED->value
                                ]
                            ],
                            [
                                'type'       => 'orGroup',
                                'parameters' => [
                                    [
                                        'type'       => 'where',
                                        'parameters' => ['state', '=', ProspectStateEnum::CONTACTED],
                                    ],
                                    [
                                        'type'       => 'where',
                                        'parameters' => [
                                            'last_contacted_at',
                                            '<=',
                                            '__date__'
                                        ],
                                    ]


                                ]
                            ],
                        ]
                    ],


                ],
                'arguments'  => [
                    '__date__' => [
                        'type'  => 'dateSubtraction',
                        'value' => [
                            'unit'     => 'week',
                            'quantity' => 1
                        ]
                    ]
                ]
            ],
        ];

        foreach ($data as $queryData) {
            $queryData['is_seeded']  = true;
            $queryData['scope_type'] = 'Shop';
            $queryData['scope_id']   = $shop->id;

            if ($query = Query::where('slug', $queryData['slug'])->where('is_seeded', true)->first()) {
                UpdateQuery::run($query, $queryData);
            } else {
                $query = StoreQuery::run($queryData);
            }
            QueryHydrateCount::run($query);
        }
    }

    public string $commandSignature = 'query:seed-shops {shops?*}';

    public function asCommand(Command $command): int
    {
        $exitCode = 0;

        foreach ($this->getShops($command) as $shop) {
            $this->handle($shop);
            $command->line("Queries seeded for $shop->name");
        }

        return $exitCode;
    }

}
