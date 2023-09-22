<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:35:41 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Website;

use App\Actions\Traits\WithActionUpdate;
use App\Models\Web\Website;
use Exception;
use Illuminate\Console\Command;
use Lorisleiva\Actions\ActionRequest;

class ResetWebsiteStructure
{
    use WithActionUpdate;

    private bool $asAction = false;

    public function handle(Website $website): Website
    {

        $website=ResetWebsiteHeader::run($website);
        $website=ResetWebsiteFooter::run($website);
        return ResetWebsiteLayout::run($website);
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
        return 'website:reset-structure {website}';
    }

    public function asCommand(Command $command): int
    {
        try {
            $website = Website::where('slug', $command->argument('website'))->firstOrFail();
        } catch (Exception $e) {
            $command->error($e->getMessage());

            return 1;
        }

        $this->handle($website);

        return 0;
    }

}
