<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 13 Jul 2023 19:48:32 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\ContentBlock;

use App\Actions\Portfolio\ContentBlock\Banners\UI\ParseContentBlockLayout;
use App\Actions\Portfolio\ContentBlock\Elasticsearch\StoreContentBlockElasticsearch;
use App\Actions\Portfolio\ContentBlock\Hydrators\ContentBlockHydrateUniversalSearch;
use App\Actions\Portfolio\ContentBlockComponent\StoreContentBlockComponent;
use App\Actions\Tenancy\Tenant\Hydrators\TenantHydrateContentBlocks;
use App\Models\Portfolio\ContentBlock;
use App\Models\Portfolio\Website;
use App\Models\Tenancy\Tenant;
use App\Models\Web\WebBlock;
use App\Models\Web\WebBlockType;
use Illuminate\Console\Command;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreContentBlock
{
    use AsAction;
    use WithAttributes;


    private bool $asAction = false;
    private Website|null $website = null;


    public function handle(Website $website, WebBlock $webBlock, array $modelData): ContentBlock
    {
        $this->website = $website;
        $layout        = $webBlock->blueprint;

        list($layout, $contentBlockComponents) = ParseContentBlockLayout::run($layout, $webBlock);


        data_set($modelData, 'web_block_type_id', $webBlock->web_block_type_id);
        data_set($modelData, 'tenant_id', app('currentTenant')->id);
        data_set($modelData, 'layout', $layout);
        data_set($modelData, 'data.website_slug', $website->slug);
        data_set($modelData, 'ulid', Str::ulid());

        /** @var ContentBlock $contentBlock */
        $contentBlock = $webBlock->contentBlocks()->create($modelData);
        if ($contentBlockComponents) {
            foreach ($contentBlockComponents as $contentBlockComponentData) {
                StoreContentBlockComponent::run(
                    contentBlock: $contentBlock,
                    modelData: $contentBlockComponentData,
                );
            }
        }

        $website->contentBlocks()->attach($contentBlock->id, [
            'tenant_id' => app('currentTenant')->id,
            'ulid'      => Str::ulid()
        ]);
        TenantHydrateContentBlocks::dispatch(app('currentTenant'));
        ContentBlockHydrateUniversalSearch::dispatch($contentBlock);
        StoreContentBlockElasticsearch::run($contentBlock);

        return $contentBlock;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->can("portfolio.edit");
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'unique:tenant.content_blocks', 'max:8'],
            'name' => ['required']
        ];
    }

    public function inWebsiteInWebBlockType(Website $website, WebBlockType $webBlockType, ActionRequest $request): ContentBlock
    {
        $request->validate();
        $webBlock = $webBlockType->webBlocks[0];

        return $this->handle($website, $webBlock, $request->validated());
    }

    public function action(Website $website, WebBlock $webBlock, array $objectData): ContentBlock
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($website, $webBlock, $validatedData);
    }

    public function getCommandSignature(): string
    {
        return 'content-block:create {tenant} {website} {web-block} {code} {name}';
    }

    public function asCommand(Command $command): void
    {
        $tenant = Tenant::where('slug', $command->argument('tenant'))->firstOrFail();
        $tenant->makeCurrent();

        $website = Website::where('slug', $command->argument('website'))->firstOrFail();
        $webBlock = WebBlock::where('slug', $command->argument('web-block'))->firstOrFail();


        $this->asAction = true;
        $this->setRawAttributes(
            [
                'code' => $command->argument('code'),
                'name' => $command->argument('name')
            ]
        );
        $validatedData = $this->validateAttributes();

        $contentBlock = $this->handle($website, $webBlock, $validatedData);

        $command->info("Done! Content block $contentBlock->code created 🎉");
    }

    public function htmlResponse(ContentBlock $contentBlock, ActionRequest $request): RedirectResponse
    {
        return match ($request->route()->getName()) {
            'models.website.web-block-type.banner.store' => redirect()->route(
                'portfolio.websites.show.banners.workshop',
                [
                    $this->website->code,
                    $contentBlock->slug
                ]
            ),
            default => redirect()->route(
                'portfolio.websites.show.banners.index',
                $this->website->code,
            ),
        };
    }
}
