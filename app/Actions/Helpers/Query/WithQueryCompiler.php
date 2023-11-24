<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 24 Nov 2023 19:17:51 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\Query;

use App\Enums\CRM\Prospect\ProspectStateEnum;
use Exception;
use Illuminate\Support\Arr;

trait WithQueryCompiler
{
    private array $arguments = [];
    private array $joins     = [];

    /**
     * @throws \Exception
     */
    public function compileConstrains(array $constrains): array
    {
        $compiledConstrains = [];

        foreach ($constrains as $type => $constrain) {
            if ($compiledConstrain = $this->compileConstrain($type, $constrain)) {
                $compiledConstrains[] = $compiledConstrain;
            }
        }
        return [
            'constrains' => $compiledConstrains,
            'arguments'  => $this->arguments,
            'joins'      => $this->joins
        ];
    }


    public function compileConstrain(string $type, array $constrain): ?array
    {
        try {
            $compiledConstrain = match ($type) {
                'with' => [
                    'type'       => 'with',
                    'parameters' => $constrain['fields']
                ],

                'tag' => [
                    'type'       => 'tag',
                    'parameters' => [
                        'tag'   => $constrain['parameters']['tag'],
                        'logic' => $constrain['parameters']['state'] ?? 'any'
                    ]
                ],

                'prospect_last_contacted' => $this->prospectLastContactedConstrain($constrain),

                default => throw new Exception('Unknown constrain type: '.$constrain['type'])
            };
        } catch (Exception) {
            $compiledConstrain = null;
        }

        return $compiledConstrain;
    }

    public function prospectLastContactedConstrain(array $constrain): array
    {


        if (Arr::get($constrain, 'state')) {
            $this->arguments['__date__'] = [
                'type'  => 'dateSubtraction',
                'value' => [
                    'unit'     => 'week',
                    'quantity' => 1
                ]

            ];

            return [
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
            ];
        } else {
            return [
                'type'       => 'where',
                'parameters' => [
                    'state',
                    '=',
                    ProspectStateEnum::NO_CONTACTED->value
                ]
            ];
        }
    }

}
