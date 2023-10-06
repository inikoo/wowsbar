<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 06 Oct 2023 12:04:50 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Webpage;

use App\Actions\Helpers\Snapshot\StoreWebpageSnapshot;
use App\Actions\Helpers\Snapshot\UpdateSnapshot;
use App\Actions\Traits\WithActionUpdate;
use App\Enums\Organisation\Web\Webpage\WebpageStateEnum;
use App\Enums\Portfolio\Snapshot\SnapshotStateEnum;
use App\Http\Resources\Web\WebpageResource;
use App\Models\Helpers\Snapshot;
use App\Models\Web\Webpage;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\ActionRequest;

class PublishWebpage
{
    use WithActionUpdate;

    public bool $isAction = false;

    public function handle(Webpage $webpage, array $modelData): Webpage
    {
        foreach ($webpage->snapshots()->where('state', SnapshotStateEnum::LIVE)->get() as $liveSnapshot) {
            UpdateSnapshot::run($liveSnapshot, [
                'state'           => SnapshotStateEnum::HISTORIC,
                'published_until' => now()
            ]);
        }

        $layout                       = Arr::get($modelData, 'layout');


        /** @var Snapshot $snapshot */
        $snapshot = StoreWebpageSnapshot::run(
            $webpage,
            [
                'state'        => SnapshotStateEnum::LIVE,
                'published_at' => now(),
                'layout'       => $layout
            ]
        );


        $compiledLayout = $snapshot->compiledLayout();



        $updateData = [
            'live_snapshot_id' => $snapshot->id,
            'compiled_layout'  => $compiledLayout,
            'state'            => WebpageStateEnum::LIVE,
        ];

        if ($webpage->state == WebpageStateEnum::IN_PROCESS or $webpage->state == WebpageStateEnum::READY) {
            $updateData['live_at'] = now();
        }

        $webpage->update($updateData);


        return $webpage;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->isAction) {
            return true;
        }

        return $request->user()->hasPermissionTo("websites.edit");
    }

    public function rules(): array
    {
        return [
            'layout'  => ['required', 'array:delay,common,components'],
            'comment' => ['sometimes', 'required', 'string', 'max:1024']
        ];
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $request->merge(
            [
                'layout' => $request->only(['delay', 'common', 'components']),
            ]
        );
    }

    public function asController(Webpage $webpage, ActionRequest $request): Webpage
    {
        $request->validate();

        return $this->handle($webpage, $request->validated());
    }

    public function action(Webpage $webpage, $modelData): Webpage
    {
        $this->isAction = true;
        $this->setRawAttributes($modelData);
        $validatedData = $this->validateAttributes();

        return $this->handle($webpage, $validatedData);
    }

    public function jsonResponse(Webpage $webpage): WebpageResource
    {
        return new WebpageResource($webpage);
    }

}
