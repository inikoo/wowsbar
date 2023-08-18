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
use App\Actions\Tenant\Portfolio\Slide\StoreSlide;
use App\Models\Portfolio\Banner;
use App\Models\Portfolio\PortfolioWebsite;
use App\Models\Tenancy\Tenant;
use Illuminate\Console\Command;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreBanner
{
    use AsAction;
    use WithAttributes;


    private bool $asAction                          = false;
    private PortfolioWebsite|null $portfolioWebsite = null;


    public function handle(PortfolioWebsite $portfolioWebsite, array $modelData): Banner
    {
        $this->portfolioWebsite = $portfolioWebsite;
        $layout                 = [
            "delay"      => 5000,
            "common"     => [
                "corners"      => [
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
        data_set($modelData, 'data.website_slug', $portfolioWebsite->slug);
        data_set($modelData, 'ulid', Str::ulid());

        /** @var Banner $banner */
        $banner = Banner::create($modelData);
        if ($slides) {
            foreach ($slides as $bannerComponentData) {
                StoreSlide::run(
                    contentBlock: $banner,
                    modelData: $bannerComponentData,
                );
            }
        }
        $portfolioWebsite->banners()->attach(
            $banner->id,
            [
                'tenant_id' => app('currentTenant')->id,
                'ulid'      => Str::ulid()
            ]
        );

        TenantHydrateBanners::dispatch(app('currentTenant'));
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

    public function rules(): array
    {
        return [
            'code' => ['required', 'unique:tenant.banners', 'max:8'],
            'name' => ['required']
        ];
    }

    public function inPortfolioWebsite(PortfolioWebsite $portfolioWebsite, ActionRequest $request): Banner
    {
        $request->validate();

        return $this->handle($portfolioWebsite, $request->validated());
    }

    public function action(PortfolioWebsite $portfolioWebsite, array $objectData): Banner
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($portfolioWebsite, $validatedData);
    }

    public function getCommandSignature(): string
    {
        return 'banner:create {tenant} {portfolio-website} {code} {name}';
    }

    public function asCommand(Command $command): void
    {
        $tenant = Tenant::where('slug', $command->argument('tenant'))->firstOrFail();
        $tenant->makeCurrent();

        $portfolioWebsite = PortfolioWebsite::where('slug', $command->argument('portfolio-website'))->firstOrFail();


        $this->asAction = true;
        $this->setRawAttributes(
            [
                'code' => $command->argument('code'),
                'name' => $command->argument('name')
            ]
        );
        $validatedData = $this->validateAttributes();

        $banner = $this->handle($portfolioWebsite, $validatedData);

        $command->info("Done! Content block $banner->code created 🎉");
    }

    public function htmlResponse(Banner $banner, ActionRequest $request): RedirectResponse
    {


        return match ($request->route()->getName()) {
            'models.portfolio-website.banner.store' => redirect()->route(
                'portfolio.portfolio-websites.show.banners.workshop',
                [
                    $this->portfolioWebsite->slug,
                    $banner->slug
                ]
            ),
            default => redirect()->route(
                'portfolio.portfolio-websites.show.banners.index',
                $this->portfolioWebsite->slug,
            ),
        };
    }
}
