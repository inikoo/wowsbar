<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Banner;

use App\Actions\CRM\Customer\Hydrators\CustomerHydrateBanners;
use App\Actions\Helpers\Snapshot\StoreBannerSnapshot;
use App\Actions\SysAdmin\Organisation\Hydrators\OrganisationHydrateBanners;
use App\Actions\Portfolio\Banner\Hydrators\BannerHydrateUniversalSearch;
use App\Actions\Portfolio\Banner\UI\ParseBannerLayout;
use App\Actions\Portfolio\PortfolioWebsite\Hydrators\PortfolioWebsiteHydrateBanners;
use App\Enums\Portfolio\Banner\BannerTypeEnum;
use App\Models\CRM\Customer;
use App\Models\Portfolio\Banner;
use App\Models\Portfolio\PortfolioWebsite;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Enum;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use LCherone\PHPPetname as PetName;

class StoreBanner
{
    use AsAction;
    use WithAttributes;

    private bool $asAction = false;

    private Customer|PortfolioWebsite $parent;
    private string $scope;
    private Customer $customer;


    public function handle(Customer|PortfolioWebsite $parent, array $modelData): Banner
    {
        $this->parent = $parent;
        $customer     = customer();

        $layout = [
            "delay"      => 5000,
            "navigation" => [
                "bottomNav" => [
                    "value"     => true,
                    "type"      => "bullet"
                ],
                "sideNav" => [
                    "value"     => true,
                    "type"      => "arrow"
                ]
            ],
            "common"     => [
                // "corners"      => [
                //     "bottomLeft" => [
                //         "type" => "slideControls"
                //     ]
                // ],
                "spaceBetween" => 0,
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

        data_set($modelData, 'data.website_slug', $parent->slug);
        data_set($modelData, 'ulid', Str::ulid());
        data_set($modelData, 'date', now());

        if (class_basename($parent) == 'PortfolioWebsite') {
            data_set($modelData, 'portfolio_website_id', $parent->id);
        }

        /** @var Banner $banner */
        $banner   = Banner::create($modelData);
        $snapshot = StoreBannerSnapshot::run(
            $banner,
            [
                'layout' => $layout
            ],
            $slides
        );

        $banner->update(
            [
                'unpublished_snapshot_id' => $snapshot->id,
                'compiled_layout'         => $snapshot->compiledLayout()
            ]
        );
        $banner->stats()->create();


        if (class_basename($parent) == 'PortfolioWebsite') {
            $parent->banners()->attach(
                $banner->id,
                [
                    'customer_id' => $customer->id,
                    'ulid'        => Str::ulid()
                ]
            );
        }


        CustomerHydrateBanners::run($customer);
        OrganisationHydrateBanners::dispatch();

        if (class_basename($parent) == 'PortfolioWebsite') {
            PortfolioWebsiteHydrateBanners::dispatch($parent);
        }

        BannerHydrateUniversalSearch::dispatch($banner);


        return $banner;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->get('customerUser')->hasPermissionTo("portfolio.banners.edit");
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        if (!$request->exists('portfolio_website_id')) {
            $count = $this->customer->portfolioWebsites()->count();
            if ($count == 1) {
                $portfolioWebsite = $request->get('customer')->portfolioWebsites()->first();

                $request->merge(['portfolio_website_id' => $portfolioWebsite->id]);
            }
        }

        if (!$request->get('name')) {
            $name = PetName::Generate(2, ' ').' banner';
            $request->merge(['name' => $name]);
        }
        if (!$request->get('type')) {
            $request->merge(['type' => BannerTypeEnum::LANDSCAPE->value]);
        }

    }

    public function rules(): array
    {
        return [
            'portfolio_website_id' => ['required', 'nullable', 'exists:portfolio_websites,id'],
            'name'                 => ['required', 'string', 'max:255'],
            'type'                 => ['required', new Enum(BannerTypeEnum::class)],
        ];
    }


    public function inCustomer(ActionRequest $request): Banner
    {
        $this->scope    = 'customer';
        $this->customer = $request->get('customer');

        $parent = customer();
        $request->validate();

        $validatedData = $request->validated();

        if ($portfolioWebsiteId = Arr::get($validatedData, 'portfolio_website_id')) {
            $parent = PortfolioWebsite::find($portfolioWebsiteId);
        }

        return $this->handle($parent, $request->validated());
    }

    public function fromGallery(ActionRequest $request): Banner
    {
        $this->scope    = 'gallery';
        $this->customer = $request->get('customer');

        $parent = $this->customer;
        $request->validate();

        $validatedData = $request->validated();

        if ($portfolioWebsiteId = Arr::get($validatedData, 'portfolio_website_id')) {
            $parent = PortfolioWebsite::find($portfolioWebsiteId);
        }

        return $this->handle($parent, $request->validated());
    }

    public function inPortfolioWebsite(PortfolioWebsite $portfolioWebsite, ActionRequest $request): Banner
    {
        $this->customer = $request->get('customer');

        $this->scope = 'portfolioWebsite';
        $request->validate();

        return $this->handle($portfolioWebsite, $request->validated());
    }

    public function action(PortfolioWebsite $portfolioWebsite, array $objectData): Banner
    {
        $this->customer = $portfolioWebsite->customer;
        data_set($objectData, 'portfolio_website_id', $portfolioWebsite->id);
        $this->asAction = true;
        $this->setRawAttributes($objectData);

        $validatedData = $this->validateAttributes();
        return $this->handle($portfolioWebsite, $validatedData);
    }

    public function getCommandSignature(): string
    {
        return 'customer:new-banner {customer} {portfolio-website} {--T|type=landscape} {--N|name=}';
    }

    public function asCommand(Command $command): int
    {
        try {
            $customer = Customer::where('slug', $command->argument('customer'))->firstOrFail();
        } catch (Exception) {
            $command->error('Customer not found');

            return 1;
        }
        Config::set('global.customer_id', $customer->id);
        $this->customer=$customer;

        $portfolioWebsite = PortfolioWebsite::where('slug', $command->argument('portfolio-website'))->firstOrFail();


        $this->asAction = true;
        $this->setRawAttributes(
            [
                'name'                 => $command->option('name') ?? PetName::Generate(2).' banner',
                'portfolio_website_id' => $portfolioWebsite->id,
                'type'                 => $command->option('type')
            ]
        );
        $validatedData = $this->validateAttributes();

        $banner = $this->handle($portfolioWebsite ?? $customer, $validatedData);

        $command->info("Done! Banner $banner->slug ($banner->name) created 🎉");

        return 0;
    }


    public function jsonResponse(Banner $banner): string
    {
        return route(
            'customer.banners.banners.workshop',
            [
                $banner->slug
            ]
        );
    }

    public function htmlResponse(Banner $banner): RedirectResponse
    {
        return redirect()->route(
            'customer.banners.banners.workshop',
            [
                $banner->slug
            ]
        );
    }
}
