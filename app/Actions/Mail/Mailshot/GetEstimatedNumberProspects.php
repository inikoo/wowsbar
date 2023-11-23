<?php

namespace App\Actions\Mail\Mailshot;

use App\Enums\CRM\Prospect\ProspectStateEnum;
use App\Models\Leads\Prospect;
use App\Models\Mail\Mailshot;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsCommand;

class GetEstimatedNumberProspects
{
    use AsCommand;

    public function handle(Mailshot $mailshot, ActionRequest $request): int
    {
        $data = [
            [
                'model_type' => class_basename(Prospect::class),
                'constrains' => [
                    'with'  => $queryBuilder['query'],
                    'group' => [
                        'where' => [
                            'state',
                            '=',
                            ProspectStateEnum::NO_CONTACTED->value
                        ],
                    ],
                    'filter' => [
                        $queryBuilder['tag']['state'] => $queryBuilder['tag']['tags']
                    ],
                ],
                'arguments' => $queryBuilder['last_contact']['state'] ? [
                    '__date__' => [
                        'type'  => 'dateSubtraction',
                        'value' => [
                            'unit'     => $queryBuilder['last_contact']['data']['unit'],
                            'quantity' => $queryBuilder['last_contact']['data']['quantity']
                        ]
                    ]
                ] : []
            ],
        ];
        if ($queryBuilder['last_contact']['state']) {
            $lastContacted = [
                'last_contacted_at',
                '<=',
                '__date__'
            ];
            $data[0]['constrains']['group']['orGroup']['where'] = $lastContacted;
        }

        return $mailshot->recipients()->count();
    }
}
