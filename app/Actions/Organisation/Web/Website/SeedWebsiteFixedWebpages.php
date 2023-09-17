<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 13 Sep 2023 15:47:08 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Web\Website;

use App\Actions\Organisation\Web\Webpage\StoreWebpage;
use App\Actions\Traits\WithActionUpdate;
use App\Models\Web\Website;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Lorisleiva\Actions\ActionRequest;

class SeedWebsiteFixedWebpages
{
    use WithActionUpdate;

    private bool $asAction = false;

    public function handle(Website $website): Website
    {
        $home = StoreWebpage::run($website, [
            'code'    => 'home',
            'url'     => '',
            'type'    => 'storefront',
            'purpose' => 'storefront',
        ]);

        $website->update(
            [
                'home_id'=> $home->id
            ]
        );

        foreach (Storage::disk('datasets')->files('webpages/'.$website->type) as $file) {
            $modelData = json_decode(Storage::disk('datasets')->get($file), true);
            data_set($modelData, 'parent_id', $home->id, overwrite: false);
            StoreWebpage::run($website, $modelData);
        }


        return $website;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->hasPermissionTo("supervisor.website");
    }


    public function asController(Website $website, ActionRequest $request): Website
    {
        return $this->handle($website);
    }

    public function getCommandSignature(): string
    {
        return 'website:seed-fixed-webpages {website}';
    }
    public function asCommand(Command $command): int
    {

        try {
            $website=Website::where('slug', $command->argument('website'))->firstOrFail();
        } catch (Exception $e) {
            $command->error($e->getMessage());
            return 1;
        }

        $this->handle($website);

        return 0;
    }

}
