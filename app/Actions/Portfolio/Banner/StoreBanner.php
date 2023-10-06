<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Banner;

use App\Actions\CRM\Customer\Hydrators\CustomerHydrateBanners;
use App\Actions\Helpers\Snapshot\StoreBannerSnapshot;
use App\Actions\Portfolio\Banner\Hydrators\BannerHydrateUniversalSearch;
use App\Actions\Portfolio\Banner\UI\ParseBannerLayout;
use App\Actions\Portfolio\PortfolioWebsite\Hydrators\PortfolioWebsiteHydrateBanners;
use App\Models\CRM\Customer;
use App\Models\Portfolio\Banner;
use App\Models\Portfolio\PortfolioWebsite;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreBanner
{
    use AsAction;
    use WithAttributes;

    private bool $asAction = false;

    private Customer|PortfolioWebsite $parent;
    private string $scope;


    public function handle(Customer|PortfolioWebsite $parent, array $modelData): Banner
    {
        $this->parent = $parent;
        $customer     =customer();

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
        list($layout, $slides) = ParseBannerLayout::run($layout);

        data_set($modelData, 'data.website_slug', $parent->slug);
        data_set($modelData, 'ulid', Str::ulid());
        if (class_basename($parent) == 'PortfolioWebsite') {
            data_set($modelData, 'portfolio_website_id', $parent->id);
        }

        /** @var Banner $banner */
        $banner  = Banner::create($modelData);
        $snapshot=StoreBannerSnapshot::run(
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
                    'customer_id' => $customer->id,
                    'ulid'        => Str::ulid()
                ]
            );

        }


        CustomerHydrateBanners::dispatch($customer);

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

        return $request->get('customerUser')->hasPermissionTo("portfolio.banners.edit");
    }

    public function rules(): array
    {
        return [
            'portfolio_website_id' => ['sometimes', 'nullable', 'exists:portfolio_websites,id'],
            'name'                 => ['required','string','max:255'],
            'published_hash'       => ['nullable','string','max:255']
        ];
    }


    public function inCustomer(ActionRequest $request): Banner
    {

        $this->scope='customer';

        $parent = customer();
        $request->validate();

        $validatedData=$request->validated();

        if($portfolioWebsiteId=Arr::get($validatedData, 'portfolio_website_id')) {
            $parent=PortfolioWebsite::find($portfolioWebsiteId);
        }

        return $this->handle($parent, $request->validated());
    }

    public function fromGallery(ActionRequest $request): Banner
    {

        $this->scope='gallery';

        $parent = customer();
        $request->validate();

        $validatedData=$request->validated();

        if($portfolioWebsiteId=Arr::get($validatedData, 'portfolio_website_id')) {
            $parent=PortfolioWebsite::find($portfolioWebsiteId);
        }

        return $this->handle($parent, $request->validated());
    }

    public function inPortfolioWebsite(PortfolioWebsite $portfolioWebsite, ActionRequest $request): Banner
    {
        $this->scope='portfolioWebsite';
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
        return 'customer:new-banner {customer} {name} {portfolio-website?}';
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

        if($website = $command->argument('portfolio-website')) {
            $portfolioWebsite = PortfolioWebsite::where('slug', $website)->firstOrFail();
        }


        $this->asAction = true;
        $this->setRawAttributes(
            [
                'name'                 => $command->argument('name'),
                'portfolio_website_id' => $portfolioWebsite->id ?? null
            ]
        );
        $validatedData = $this->validateAttributes();

        $banner = $this->handle($portfolioWebsite ?? $customer, $validatedData);

        $command->info("Done! Banner $banner->slug created 🎉");
        return 0;
    }

    public function htmlResponse(Banner $banner): RedirectResponse
    {


        if (class_basename($this->parent) == 'PortfolioWebsite') {
            return redirect()->route(
                'customer.portfolio.websites.show.banners.workshop',
                [
                    $this->parent->slug,
                    $banner->slug
                ]
            );
        }

        return redirect()->route(
            'customer.caas.banners.workshop',
            [
                $banner->slug
            ]
        );
    }
}
