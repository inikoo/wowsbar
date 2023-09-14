<?php

namespace App\Actions\Organisation\Web\Website\UI;

use App\Models\Organisation\Web\Website;
use Lorisleiva\Actions\Concerns\AsObject;
use Arr;

class GetWebsiteWorkshopMenu
{
    use AsObject;

    public function handle(Website $website): array
    {
        return [
            'data'=> Arr::get($website->structure,'menu')
        ];
    }
}
