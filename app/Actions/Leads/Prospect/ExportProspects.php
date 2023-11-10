<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:12 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Leads\Prospect;

use App\Actions\Traits\WithExportData;
use App\Exports\CRM\ProspectsExport;
use App\Models\Market\Shop;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportProspects
{
    use AsAction;
    use WithAttributes;
    use WithExportData;

    /**
     * @throws \Throwable
     */
    public function handle(array $objectData): BinaryFileResponse
    {
        $type = $objectData['type'];

        if($parent = request()->route('shop')) {
            $parent = Shop::whereSlug($parent)->firstOrFail();
        } else {
            $parent = organisation();
        }

        return $this->export(new ProspectsExport($parent), 'prospects', $type);
    }

    /**
     * @throws \Throwable
     */
    public function asController(ActionRequest $request): BinaryFileResponse
    {
        $this->setRawAttributes($request->all());
        $this->validateAttributes();

        return $this->handle($request->all());
    }
}
