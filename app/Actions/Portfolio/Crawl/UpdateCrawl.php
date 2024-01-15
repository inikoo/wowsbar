<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Crawl;

use App\Actions\Traits\WithActionUpdate;
use App\Models\Portfolio\Crawl;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsCommand;
use Lorisleiva\Actions\Concerns\WithAttributes;

class UpdateCrawl
{
    use WithAttributes;
    use AsCommand;
    use WithActionUpdate;

    public function handle(Crawl $crawl, $modelData): Model
    {
        return $this->update($crawl, $modelData);
    }

    public function asController(Crawl $crawl, ActionRequest $request): Model
    {
        return $this->handle($crawl, $request->all());
    }
}
