<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 02 Nov 2023 15:07:11 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\SourceFetch\Aurora;

use App\Actions\Leads\Prospect\StoreProspect;
use App\Actions\Leads\Prospect\UpdateProspect;
use App\Models\Leads\Prospect;
use App\Models\Market\Shop;
use App\Services\SourceService;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class FetchProspects extends FetchAction
{
    public string $commandSignature = 'fetch:prospects {au_database} {--S|shop= : Aurora Store Key} {--s|source_id=} {--N|only_new : Fetch only new}';


    public function handle(SourceService $source, int $sourceId): ?Prospect
    {
        $shop = Shop::first();

        if ($prospectData = $source->fetchProspect($sourceId)) {
            if ($prospect = Prospect::withTrashed()->whereJsonContains('data->source->source_id', Arr::get($prospectData, 'prospect.data.source.source_id'))->first()) {
                $prospect = UpdateProspect::make()->action($shop, $prospect, $prospectData['prospect']);
            } else {
                $prospect = StoreProspect::make()->action($shop, $prospectData['prospect']);
            }


            DB::connection('aurora')->table('Prospect Dimension')
                ->where('Prospect Key', $prospect->source_id)
                ->update(['wowsbar_id' => $prospect->id]);

            return $prospect;
        }

        return null;
    }

    public function getModelsQuery(): Builder
    {
        $query = DB::connection('aurora')
            ->table('Prospect Dimension')
            ->select('Prospect Key as source_id')
            ->orderBy('source_id');

        if ($this->onlyNew) {
            $query->whereNull('wowsbar_id');
        }

        if ($this->auShop) {
            $query->where('Prospect Store Key', $this->auShop);
        }

        return $query;
    }

    public function count(): ?int
    {
        $query = DB::connection('aurora')->table('Prospect Dimension');

        if ($this->onlyNew) {
            $query->whereNull('wowsbar_id');
        }
        if ($this->auShop) {
            $query->where('Prospect Store Key', $this->auShop);
        }

        return $query->count();
    }
}
