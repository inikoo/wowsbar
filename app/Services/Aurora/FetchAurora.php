<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 02 Nov 2023 15:07:11 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Services\Aurora;

use App\Services\SourceService;

class FetchAurora
{
    use WithAuroraParsers;


    protected ?array $parsedData;
    protected ?object $auroraModelData;
    protected SourceService $source;

    public function __construct(SourceService $source)
    {
        $this->source          = $source;
        $this->parsedData      = null;
        $this->auroraModelData = null;
    }

    public function fetch(int $id): ?array
    {
        $this->auroraModelData = $this->fetchData($id);

        if ($this->auroraModelData) {
            $this->parseModel();
        }

        return $this->parsedData;
    }

    protected function fetchData($id): object|null
    {
        return null;
    }

    protected function parseModel(): void
    {
    }
}
