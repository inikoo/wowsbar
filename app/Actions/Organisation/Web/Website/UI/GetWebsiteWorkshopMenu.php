<?php

namespace App\Actions\Organisation\Web\Website\UI;

use App\Models\Web\Website;
use Arr;
use Lorisleiva\Actions\Concerns\AsObject;

class GetWebsiteWorkshopMenu
{
    use AsObject;

    public function handle(Website $website): array
    {
        return [
            'data'=> Arr::get($website->structure, 'menu')
        ];
    }
}
