<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:35:41 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Website;

use App\Actions\Traits\WithActionUpdate;
use App\Enums\Organisation\Web\Website\WebsiteStateEnum;
use App\Models\Web\Website;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;

class UpdateWebsiteState
{
    use WithActionUpdate;

    private bool $asAction = false;

    public function handle(Website $website, array $modelData): Website
    {
        if ($website->state == WebsiteStateEnum::IN_PROCESS and Arr::get($modelData, 'state') == WebsiteStateEnum::LIVE->value) {
            data_set($modelData, 'launched_at', now());
        }

        if ($website->state != WebsiteStateEnum::CLOSED  and Arr::get($modelData, 'state') == WebsiteStateEnum::CLOSED->value) {
            data_set($modelData, 'closed_at', now());
        }

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



    public function rules(): array
    {
        return [
            'state'  => ['required',
                         Rule::in([
                             WebsiteStateEnum::LIVE->value,
                             WebsiteStateEnum::CLOSED->value
                         ])
                ],
            'status' => ['required', 'boolean'],

        ];
    }

    public function asController(Website $website, ActionRequest $request): Website
    {
        $request->validate();

        return $this->handle($website, $request->validated());
    }


    public string $commandSignature = 'website:change-state {website} {state}';

    public function asCommand(Command $command): int
    {
        $this->asAction = true;

        try {
            $website = Website::where('slug', $command->argument('website'))->firstOrFail();
        } catch (Exception $e) {
            $command->error($e->getMessage());

            return 1;
        }
        $status = null;
        $state  = $command->argument('state');

        if (in_array($state, ['m', 'maintenance', 'down', 'off'])) {
            $state  = WebsiteStateEnum::LIVE->value;
            $status = false;
        }
        if (in_array($state, ['on', 'up', 'launch'])) {
            $state  = WebsiteStateEnum::LIVE->value;
            $status = true;
        }

        if (is_null($status)) {
            $status = match ($state) {
                WebsiteStateEnum::LIVE->value => true,
                default                       => false
            };
        }


        $this->setRawAttributes([
            'state'  => $state,
            'status' => $status
        ]);

        try {
            $validatedData = $this->validateAttributes();
        } catch (Exception $e) {
            $command->error($e->getMessage());

            return 1;
        }

        $website = $this->handle($website, $validatedData);

        $command->info("Website $website->slug state change to {$website->getAttribute('condition')}");

        return 0;
    }

}
