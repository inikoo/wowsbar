<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:35:41 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\WebpageVariant;

use App\Actions\CRM\Customer\UI\IndexCustomers;
use App\Actions\InertiaAction;
use App\Actions\Mail\Mailshot\IndexMailshots;
use App\Actions\Catalogue\Product\UI\IndexProducts;
use App\Enums\UI\WebpageTabsEnum;
use App\Http\Resources\Web\WebpageResource;
use App\Models\Market\ShopProductCategory;
use App\Models\Market\Shop;
use App\Models\Web\WebpageVariant;
use Inertia\Inertia;
use Inertia\Response;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;

/**
 * @property WebpageVariant $webpageVariant
 */

class ShowWebpageVariant extends InertiaAction
{
    public function handle(WebpageVariant $webpageVariant): WebpageVariant
    {
        return $webpageVariant;
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->hasPermissionTo('websites.edit');
        return $request->user()->hasPermissionTo("websites.view");
    }

    public function inOrganisation(WebpageVariant $webpageVariant, ActionRequest $request): WebpageVariant
    {
        $this->initialisation($request)->withTab(WebpageTabsEnum::values());
        return $this->handle($webpageVariant);
    }

    public function inShop(Shop $shop, WebpageVariant $webpageVariant, ActionRequest $request): WebpageVariant
    {
        $this->initialisation($request)->withTab(WebpageTabsEnum::values());
        return $this->handle($webpageVariant);
    }
    public function inShopInDepartment(Shop $shop, ShopProductCategory $department, WebpageVariant $webpageVariant, ActionRequest $request): WebpageVariant
    {
        $this->initialisation($request)->withTab(WebpageTabsEnum::values());
        return $this->handle($webpageVariant);
    }

    public function htmlResponse(WebpageVariant $webpageVariant): Response
    {
        $this->validateAttributes();


        return Inertia::render(
            'Market/WebpageVariant',
            [
                'title'       => __('webpage variant'),
                'breadcrumbs' => $this->getBreadcrumbs($webpageVariant),
                'pageHead'    => [
                    'title' => $webpageVariant->code,
                    'edit'  => $this->canEdit ? [
                        'route' => [
                            'name'       => preg_replace('/show$/', 'edit', $request->route()->getName()),
                            'parameters' => array_values($this->originalParameters)
                        ]
                    ] : false,
                ],
                'tabs'=> [
                    'current'    => $this->tab,
                    'navigation' => WebpageTabsEnum::navigation()
                ],
                /*
                WebpageTabsEnum::CUSTOMERS->value => $this->tab == WebpageTabsEnum::CUSTOMERS->value ?
                    fn () => CustomerResource::collection(IndexCustomers::run($this->family))
                    : Inertia::lazy(fn () => CustomerResource::collection(IndexCustomers::run($this->family))),
                WebpageTabsEnum::MAILSHOTS->value => $this->tab == WebpageTabsEnum::MAILSHOTS->value ?
                    fn () => MailshotResource::collection(IndexMailshots::run($this->family))
                    : Inertia::lazy(fn () => MailshotResource::collection(IndexMailshots::run($this->family))),
                WebpageTabsEnum::PRODUCTS->value => $this->tab == WebpageTabsEnum::PRODUCTS->value ?
                    fn () => ProductResource::collection(IndexProducts::run($this->family))
                    : Inertia::lazy(fn () => ProductResource::collection(IndexProducts::run($this->family))),
*/


            ]
        )->table(IndexCustomers::make()->tableStructure($webpageVariant))
            ->table(IndexMailshots::make()->tableStructure($webpageVariant))
            ->table(IndexProducts::make()->tableStructure($webpageVariant));
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $this->fillFromRequest($request);

        $this->set('canEdit', $request->user()->hasPermissionTo('hr.edit'));
        $this->set('canViewUsers', $request->user()->hasPermissionTo('users.view'));
    }

    #[Pure] public function jsonResponse(WebpageVariant $webpageVariant): WebpageResource
    {
        return new WebpageResource($webpageVariant);
    }
}
