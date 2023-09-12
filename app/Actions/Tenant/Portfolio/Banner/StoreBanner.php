<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 14:12:03 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Banner;

use App\Actions\Tenancy\Tenant\Hydrators\TenantHydrateBanners;
use App\Actions\Tenant\Portfolio\Banner\Hydrators\BannerHydrateUniversalSearch;
use App\Actions\Tenant\Portfolio\Banner\UI\ParseBannerLayout;
use App\Actions\Tenant\Portfolio\PortfolioWebsite\Hydrators\PortfolioWebsiteHydrateBanners;
use App\Actions\Tenant\Portfolio\Snapshot\StoreSnapshot;
use App\Models\Portfolio\Banner;
use App\Models\Portfolio\PortfolioWebsite;
use App\Models\Tenancy\Tenant;
use Illuminate\Console\Command;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreBanner
{
    use AsAction;
    use WithAttributes;

    private bool $asAction = false;

    private Tenant|PortfolioWebsite $parent;


    public function handle(Tenant|PortfolioWebsite $parent, array $modelData): Banner
    {
        $this->parent = $parent;

        $layout = [
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
        list($layout, $slides, $hash) = ParseBannerLayout::run($layout);

        data_set($modelData, 'tenant_id', app('currentTenant')->id);
        data_set($modelData, 'data.website_slug', $parent->slug);
        data_set($modelData, 'ulid', Str::ulid());
        if (class_basename($parent) == 'PortfolioWebsite') {
            data_set($modelData, 'portfolio_website_id', $parent->id);
        }

        /** @var Banner $banner */
        $banner  = Banner::create($modelData);
        $snapshot=StoreSnapshot::run(
            $banner,
            [
                'layout'=> $layout
            ],
            $slides
        );

        $banner->update(
            [
                'unpublished_snapshot_id'=> $snapshot->id,
                'compiled_layout'        => $snapshot->compiledLayout()
            ]
        );
        $banner->stats()->create();



        if (class_basename($parent) == 'PortfolioWebsite') {
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
        //  StoreBannerElasticsearch::run($banner);

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
            'portfolio_website_id' => ['sometimes', 'nullable', 'exists:tenant.portfolio_websites,id'],
            'code'                 => ['required', 'unique:tenant.banners', 'max:8'],
            'name'                 => ['required']
        ];
    }


    public function inTenant(ActionRequest $request): Banner
    {
        //dd( $request->input());

        $parent = app('currentTenant');
        $request->validate();

        $validatedData=$request->validated();

        if($portfolioWebsiteId=Arr::get($validatedData, 'portfolio_website_id')) {
            $parent=PortfolioWebsite::find($portfolioWebsiteId);
        }

        return $this->handle($parent, $request->validated());
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
        return 'banner:create {tenant} {code} {name} {portfolio-website?}';
    }

    public function asCommand(Command $command): void
    {
        $tenant = Tenant::where('slug', $command->argument('tenant'))->firstOrFail();
        $tenant->makeCurrent();

        if($website = $command->argument('portfolio-website')) {
            $portfolioWebsite = PortfolioWebsite::where('slug', $website)->firstOrFail();
        }


        $this->asAction = true;
        $this->setRawAttributes(
            [
                'code'                 => $command->argument('code'),
                'name'                 => $command->argument('name'),
                'portfolio_website_id' => $portfolioWebsite->id ?? null
            ]
        );
        $validatedData = $this->validateAttributes();

        $banner = $this->handle($portfolioWebsite ?? $tenant, $validatedData);

        $command->info("Done! Content block $banner->code created 🎉");
    }

    public function htmlResponse(Banner $banner): RedirectResponse
    {
        if (class_basename($this->parent) == 'PortfolioWebsite') {
            return redirect()->route(
                'tenant.portfolio.websites.show.banners.workshop',
                [
                    $this->parent->slug,
                    $banner->slug
                ]
            );
        }

        return redirect()->route(
            'tenant.portfolio.banners.workshop',
            [
                $banner->slug
            ]
        );
    }
}
