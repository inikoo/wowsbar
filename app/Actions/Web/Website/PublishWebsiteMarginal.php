<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 06 Oct 2023 10:05:33 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Website;

use App\Actions\Helpers\Snapshot\StoreWebsiteSnapshot;
use App\Actions\Helpers\Snapshot\UpdateSnapshot;

use App\Actions\Traits\WithActionUpdate;
use App\Enums\Portfolio\Snapshot\SnapshotStateEnum;
use App\Models\Helpers\Snapshot;
use App\Models\Web\Website;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\ActionRequest;

class PublishWebsiteMarginal
{
    use WithActionUpdate;

    public bool $isAction = false;

    public function handle(Website $website, string $marginal, array $modelData): Website
    {
        foreach ($website->snapshots()->where('scope', $marginal)->where('state', SnapshotStateEnum::LIVE)->get() as $liveSnapshot) {
            UpdateSnapshot::run($liveSnapshot, [
                'state'           => SnapshotStateEnum::HISTORIC,
                'published_until' => now()
            ]);
        }


        /** @var Snapshot $snapshot */
        $snapshot = StoreWebsiteSnapshot::run(
            $website,
            [
                'state'        => SnapshotStateEnum::LIVE,
                'published_at' => now(),
                'layout'       => Arr::get($modelData, 'layout'),
                'scope'        => $marginal
            ],
        );


        $compiledLayout = $snapshot->compiledLayout();
        $updateData     = [
            "live_{$marginal}_snapshot_id" => $snapshot->id,
            "compiled_layout->$marginal"   => $compiledLayout
        ];

        $website->update($updateData);


        return $website;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->isAction) {
            return true;
        }

        return $request->user()->hasPermissionTo("website.edit");
    }

    public function rules(): array
    {
        return [
            'layout'  => ['required', 'array:delay,common,components'],
            'comment' => ['sometimes', 'required', 'string', 'max:1024']
        ];
    }


    public function header(Website $website, ActionRequest $request): Website
    {
        $request->validate();

        return $this->handle($website, 'header', $request->validated());
    }

    public function footer(Website $website, ActionRequest $request): Website
    {
        $request->validate();

        return $this->handle($website, 'footer', $request->validated());
    }

    public function action(Website $website, $marginal, $modelData): Website
    {
        $this->isAction = true;
        $this->setRawAttributes($modelData);
        $validatedData = $this->validateAttributes();

        return $this->handle($website, $marginal, $validatedData);
    }


}
