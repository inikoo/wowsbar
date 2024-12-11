<?php

namespace App\Actions\Portfolio\Announcement\UI;

use Lorisleiva\Actions\Concerns\AsAction;

class GetSelectedPathFromDomain
{
    use AsAction;

    public function handle(string $domain): string|null
    {
        $path = $domain ? preg_replace('/^(https?:\/\/)?(www\.)?[^\/]+\/?(.*)$/', '$3', $domain) : null;

        return $path === '' ? null : $path;
    }
}
