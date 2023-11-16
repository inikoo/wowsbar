<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 08:23:57 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Leads\Prospect\Queries;

use App\Actions\Helpers\Query\Hydrators\QueryHydrateCount;
use App\Actions\Helpers\Query\StoreQuery;
use App\Actions\Helpers\Query\UpdateQuery;
use App\Actions\Leads\Prospect\WithProspectPrepareForValidation;
use App\Enums\CRM\Prospect\ProspectContactStateEnum;
use App\Models\Helpers\Query;
use App\Models\Leads\Prospect;
use App\Models\Market\Shop;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreProspectQuery
{
    use AsAction;
    use WithAttributes;
    use WithProspectPrepareForValidation;

    private bool $asAction = false;
    /**
     * @var \App\Models\Market\Shop
     */
    private Shop $scope;

    public function handle(Shop $shop, array $modelData): Query
    {
        $query = $modelData['query_builder'];

        $data = [
            [
                'slug' => Str::slug($modelData['name']),
                'name' => $modelData['name'],
                'model_type' => class_basename(Prospect::class),
                'constrains' => [
                    'with' => $query['query'],
                    'group' => [
                        'where' => [
                            'contact_state',
                            '=',
                            ProspectContactStateEnum::NO_CONTACTED->value
                        ],
                    ],
                    $query['tag']['state'] => $query['tag']['tags']
                ],
                'arguments' => $query['last_contact']['state'] ? [
                    '__date__' => [
                        'type' => 'dateSubtraction',
                        'value' => [
                            'unit' => $query['last_contact']['data']['unit'],
                            'quantity' => $query['last_contact']['data']['quantity']
                        ]
                    ]
                ] : []
            ],
        ];

        if($query['last_contact']['state']) {
            $lastContacted = [
                'last_contacted_at',
                '<=',
                '__date__'
            ];
            $data[0]['constrains']['group']['orGroup']['where'] = $lastContacted;
        }

        foreach ($data as $queryData) {
            $queryData['is_seeded'] = true;
            $queryData['scope_type'] = 'Shop';
            $queryData['scope_id'] = $shop->id;

            if ($query = Query::where('slug', $queryData['slug'])->where('is_seeded', true)->first()) {
                UpdateQuery::run($query, $queryData);
            } else {
                $query = StoreQuery::run($queryData);
            }
            QueryHydrateCount::run($query);
        }

        return $query;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->hasPermissionTo("crm.edit");
    }

    public function inShop(Shop $shop, ActionRequest $request): Query
    {
        $this->scope = $shop;
        $this->fillFromRequest($request);

        return $this->handle($shop, $this->validateAttributes());
    }

    public function rules(ActionRequest $request): array
    {
        return [
            'name'  => ['required', 'string', 'max:255'],
            'query_builder.*' => ['required', 'array']
        ];
    }

    public function htmlResponse(Query $query): RedirectResponse
    {
        return Redirect::route('org.crm.shop.prospects.lists.index', [
            $query->scope->slug
        ]);
    }
}
