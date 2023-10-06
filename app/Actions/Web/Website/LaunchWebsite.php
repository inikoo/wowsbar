<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 06 Oct 2023 10:05:33 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Website;

use App\Actions\Traits\WithActionUpdate;
use App\Enums\Organisation\Web\Website\WebsiteStateEnum;
use App\Models\Web\Website;
use Exception;
use Illuminate\Console\Command;
use Lorisleiva\Actions\ActionRequest;

class LaunchWebsite
{
    use WithActionUpdate;

    private bool $asAction = false;

    public function handle(Website $website): Website
    {
        if ($website->state != WebsiteStateEnum::IN_PROCESS) {
            abort(419);
        }


        PublishWebsiteMarginal::run($website, 'header', ['layout' => $website->unpublishedHeaderSnapshot->layout]);
        PublishWebsiteMarginal::run($website, 'footer', ['layout' => $website->unpublishedFooterSnapshot->layout]);

        $modelData = [
            'state'       => WebsiteStateEnum::LIVE,
            'status'      => true,
            'launched_at' => now()
        ];

        return $this->update($website, $modelData);
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->hasPermissionTo("supervisor.website");
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        if (!$request->exists('status') and $request->has('state')) {
            $status = match ($request->get('state')) {
                WebsiteStateEnum::LIVE->value => true,
                default                       => false
            };
            $request->merge(['status' => $status]);
        }
    }


    public function asController(Website $website, ActionRequest $request): Website
    {
        $request->validate();

        return $this->handle($website);
    }


    public string $commandSignature = 'website:launch {website}';

    public function asCommand(Command $command): int
    {
        $this->asAction = true;

        try {
            $website = Website::where('slug', $command->argument('website'))->firstOrFail();
        } catch (Exception $e) {
            $command->error($e->getMessage());

            return 1;
        }

        if ($website->state != WebsiteStateEnum::IN_PROCESS) {
            $command->error('Can not launch, Website is '.$website->state->value);

            return 1;
        }


        $this->handle($website);

        $command->info("Website launched 🚀");

        return 0;
    }

}
