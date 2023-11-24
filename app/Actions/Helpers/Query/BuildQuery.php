<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 03 Nov 2023 18:40:15 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\Query;

use App\Models\Helpers\Query;
use App\Models\Leads\Prospect;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Lorisleiva\Actions\Concerns\AsObject;
use Spatie\QueryBuilder\QueryBuilder;

class BuildQuery
{
    use AsObject;

    public function handle(Query $query): QueryBuilder
    {
        $queryBuilder = QueryBuilder::for(Prospect::class);
        if ($query->scope_type == 'Shop') {
            $queryBuilder->where('shop_id', $query->scope_id);
        }

        foreach ($query->constrains as $constrainData) {
            $constrainType = $constrainData['type'];


            if ($constrainType == 'group') {
                $subConstrainData = $constrainData['parameters'];

                $queryBuilder
                    ->where(
                        function (Builder $subQueryBuilder) use ($subConstrainData, $query) {
                            foreach ($subConstrainData as $constrainData) {

                                $constrainType   = $constrainData['type'];

                                $subQueryBuilder = $this->addConstrain($subQueryBuilder, $constrainType, $constrainData['parameters'], $query);
                            }
                        }
                    );
            } elseif ($constrainType == 'orGroup') {
                $subConstrainData = $constrainData['parameters'];
                $queryBuilder
                    ->where(
                        function (Builder $subQueryBuilder) use ($subConstrainData, $query) {
                            foreach ($subConstrainData as $constrainData) {
                                $constrainType   = $constrainData['type'];
                                $subQueryBuilder = $this->addConstrain($subQueryBuilder, $constrainType, $constrainData['parameters'], $query);
                            }
                        }
                    );
            } else {
                $queryBuilder = $this->addConstrain($queryBuilder, $constrainType, $constrainData['parameters'], $query);
            }
        }


        return $queryBuilder;
    }

    protected function addConstrain($queryBuilder, $constrainType, $constrainData, $query)
    {

        if ($constrainType == 'with') {
            if (is_array($constrainData)) {
                foreach ($constrainData as $constrain) {
                    $queryBuilder->whereNotNull($constrain);
                }
            } else {
                $queryBuilder->whereNotNull($constrainData);
            }
        } elseif ($constrainType == 'without') {
            $queryBuilder->whereNull($constrainData);
        } elseif ($constrainType == 'where') {
            $value = $constrainData[2];
            if (preg_match('/^__.+__$/', $value)) {
                $value = $this->getArgument(Arr::get($query->arguments, $value));
            }
            $queryBuilder->where($constrainData[0], $constrainData[1], $value);
        } elseif ($constrainType == 'whereIn') {
            $queryBuilder->whereIn($constrainData[0], $constrainData[1]);
        } elseif ($constrainType == 'orWhereNull') {
            $queryBuilder->orWhereNull($constrainData);
        } elseif ($constrainType == 'Group') {
            $queryBuilder
                ->where(
                    function (Builder $subQueryBuilder) use ($constrainData, $query) {
                        foreach ($constrainData as  $subConstrainData) {
                            $subQueryBuilder = $this->addConstrain($subQueryBuilder, $subConstrainData['type'], $subConstrainData['parameters'], $query);
                        }
                    }
                );
        } elseif ($constrainType == 'orGroup') {
            $queryBuilder
                ->orWhere(
                    function (Builder $subQueryBuilder) use ($constrainData, $query) {
                        foreach ($constrainData as $subConstrainData) {
                            $subQueryBuilder = $this->addConstrain($subQueryBuilder, $subConstrainData['type'], $subConstrainData['parameters'], $query);
                        }
                    }
                );
        } elseif ($constrainType == 'filter') {
            if (Arr::get($constrainData, 'all')) {
                $queryBuilder->withAllTags($constrainData['all'], 'crm');
            } elseif (Arr::get($constrainData, 'any')) {
                $queryBuilder->withAnyTags($constrainData['any'], 'crm');
            }
        }

        return $queryBuilder;
    }

    protected function getArgument($argumentData): ?\DateTime
    {
        if (!$argumentData) {
            return null;
        }

        $value = null;
        if (Arr::get($argumentData, 'type') == 'dateSubtraction') {
            $date = Carbon::now();
            $date->sub(Arr::get($argumentData, 'value.quantity'), Arr::get($argumentData, 'value.unit'));
            $value = $date->toDateTime();
        }


        return $value;
    }

}
