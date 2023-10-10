<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 06 Oct 2023 12:04:50 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Webpage;

use App\Actions\Helpers\Deployment\StoreDeployment;
use App\Actions\Helpers\Snapshot\StoreWebpageSnapshot;
use App\Actions\Helpers\Snapshot\UpdateSnapshot;
use App\Actions\Traits\WithActionUpdate;
use App\Enums\Organisation\Web\Webpage\WebpageStateEnum;
use App\Enums\Portfolio\Snapshot\SnapshotStateEnum;
use App\Models\Helpers\Snapshot;
use App\Models\Web\Webpage;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\ActionRequest;
use function MongoDB\BSON\toJSON;

class PublishWebpage
{
    use WithActionUpdate;

    public bool $isAction = false;

    public function handle(Webpage $webpage, array $modelData): Webpage
    {
        $firstCommit = false;
        if ($webpage->state == WebpageStateEnum::IN_PROCESS or $webpage->state == WebpageStateEnum::READY) {
            $firstCommit = true;
        }

        foreach ($webpage->snapshots()->where('state', SnapshotStateEnum::LIVE)->get() as $liveSnapshot) {
            UpdateSnapshot::run($liveSnapshot, [
                'state' => SnapshotStateEnum::HISTORIC,
                'published_until' => now()
            ]);
        }

        $layout = $webpage->unpublishedSnapshot->layout;
        $html = $layout['html'][0]['html'];

        $doc = new \DOMDocument('1.0', 'utf-8');
        @$doc->loadHTML($html);

        $layout['html'] = $this->getElementsByClass($doc, 'section', 'wowsbar-html');

        /** @var Snapshot $snapshot */
        $snapshot = StoreWebpageSnapshot::run(
            $webpage,
            [
                'state' => SnapshotStateEnum::LIVE,
                'published_at' => now(),
                'layout' => $layout,
                'first_commit' => $firstCommit,
                'comment' => Arr::get($modelData, 'comment'),
                'publisher_id' => Arr::get($modelData, 'publisher_id'),
                'publisher_type' => Arr::get($modelData, 'publisher_type'),
            ]
        );

        StoreDeployment::run(
            $webpage,
            [
                'snapshot_id' => $snapshot->id,
            ]
        );

        $compiledLayout = $snapshot->compiledLayout();


        $updateData = [
            'live_snapshot_id' => $snapshot->id,
            'compiled_layout' => $compiledLayout,
            'published_checksum' => md5(json_encode($snapshot->layout)),
            'state' => WebpageStateEnum::LIVE,
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
            'comment' => ['sometimes', 'required', 'string', 'max:1024'],
            'publisher_id' => ['sometimes'],
            'publisher_type' => ['sometimes', 'string'],
        ];
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $request->merge(
            [
                'publisher_id' => $request->user()->id,
                'publisher_type' => 'OrganisationUser'
            ]
        );
    }

    public function asController(Webpage $webpage, ActionRequest $request): string
    {
        $request->validate();
        $this->handle($webpage, $request->validated());

        return "🚀";
    }

    public function action(Webpage $webpage, $modelData): Webpage
    {
        $this->isAction = true;
        $this->setRawAttributes($modelData);
        $validatedData = $this->validateAttributes();

        return $this->handle($webpage, $validatedData);
    }

    public function getElementsByClass(&$parentNode, $tagName, $className): array
    {
        $childNodes = [];

        $childNodeList = $parentNode->getElementsByTagName($tagName);
        for ($i = 0; $i < $childNodeList->length; $i++) {
            $childNodes = [];
            $temp = $childNodeList->item($i);
            if (stripos($temp->getAttribute('class'), $className) !== false) {
                $nodes = $temp;
                $children = $nodes->childNodes;
                foreach ($children as $child) {
                    $childNodes[] = [
                        'section' => $className,
                        'content' => $this->convertToHTML($child)
                    ];
                }
            }
        }

        return $childNodes;
    }

    public function convertToHTML($child): string
    {
        return $child->ownerDocument->saveXML( $child );
    }
}
