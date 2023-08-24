<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 14:12:03 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Banner;

use App\Actions\Tenancy\Tenant\Hydrators\TenantHydrateBanners;
use App\Actions\Tenant\Portfolio\Banner\Elasticsearch\StoreBannerElasticsearch;
use App\Actions\Tenant\Portfolio\Banner\Hydrators\BannerHydrateUniversalSearch;
use App\Actions\Tenant\Portfolio\Banner\UI\ParseBannerLayout;
use App\Actions\Tenant\Portfolio\PortfolioWebsite\Hydrators\PortfolioWebsiteHydrateBanners;
use App\Models\Portfolio\Banner;
use App\Models\Portfolio\PortfolioWebsite;
use App\Models\Tenancy\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreBannerFromGallery
{
    use AsAction;
    use WithAttributes;


    private bool $asAction                          = false;
    private PortfolioWebsite|null $portfolioWebsite = null;


    public function handle(Tenant|PortfolioWebsite $parent, array $modelData): Banner
    {
        if(class_basename($parent) == 'PortfolioWebsite') {
            $this->portfolioWebsite = $parent;
        }

        $layout = [
            "delay"  => 5000,
            "common" => [
                "corners" => [
                    "bottomLeft" => [
                        "type" => "slideControls"
                    ]
                ],
                "centralStage" => [
                    "title"    => null,
                    "subtitle" => null,
                    "text"     => null
                ]
            ],
            "components" => [
            ]
        ];
        list($layout, $slides) = ParseBannerLayout::run($layout);

        data_set($modelData, 'tenant_id', app('currentTenant')->id);
        data_set($modelData, 'layout', $layout);

        if(class_basename($parent) == 'PortfolioWebsite') {
            data_set($modelData, 'data.website_slug', $parent->slug);
        }

        data_set($modelData, 'ulid', Str::ulid());
        data_set($modelData, 'code', Str::random(3));

        /** @var Banner $banner */
        $banner = Banner::create(\Arr::except($modelData, ['images']));
        foreach ($modelData['images'] as $image) {
            data_set($modelData, 'image_id', $image);
            data_set($modelData, 'ulid', Str::ulid());
            $banner->slides()->create(\Arr::except($modelData, ['images', 'layout', 'data', 'code', 'name']));
        }

        if(class_basename($parent) == 'PortfolioWebsite') {
            $parent->banners()->attach(
                $banner->id,
                [
                    'tenant_id' => app('currentTenant')->id,
                    'ulid'      => Str::ulid()
                ]
            );
        }

        TenantHydrateBanners::dispatch(app('currentTenant'));

        if(class_basename($parent) == 'PortfolioWebsite') {
            PortfolioWebsiteHydrateBanners::dispatch($parent);
        }

        BannerHydrateUniversalSearch::dispatch($banner);
        StoreBannerElasticsearch::run($banner);

        return $banner;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->can("portfolio.edit");
    }

    public function inPortfolioWebsite(PortfolioWebsite $portfolioWebsite, ActionRequest $request): Banner
    {
        return $this->handle($portfolioWebsite, $request->all());
    }

    public function inTenant(ActionRequest $request): Banner
    {
        return $this->handle(app('currentTenant'), $request->all());
    }

    public function getCommandSignature(): string
    {
        return 'banner:gallery-create {name} {tenant} {images} {website?}';
    }

    public function asCommand(Command $command): void
    {
        $tenant = Tenant::where('slug', $command->argument('tenant'))->firstOrFail();
        $tenant->makeCurrent();

        if($website = $command->argument('website')) {
            $portfolioWebsite = PortfolioWebsite::where('slug', $website)->firstOrFail();
        }

        $this->asAction = true;
        $this->setRawAttributes(
            [
                'name'   => $command->argument('name'),
                'images' => [$command->argument('images')]
            ]
        );

        $banner = $this->handle($portfolioWebsite ?? $tenant, $this->attributes);

        $command->info("Done! Content block $banner->code created 🎉");
    }
}
