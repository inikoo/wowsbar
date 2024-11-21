<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Announcement\UI;

use App\Actions\InertiaAction;
use App\Models\CRM\Customer;
use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class CreateAnnouncement extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        return $request->get('customerUser')->hasPermissionTo('portfolio.banners.edit');
    }

    public function inCustomer(ActionRequest $request): Response|RedirectResponse
    {
        $this->initialisation($request);

        return $this->handle(customer(), $request);
    }

    public function inPortfolioWebsite(PortfolioWebsite $portfolioWebsite, ActionRequest $request): Response|RedirectResponse
    {
        $this->initialisation($request);

        return $this->handle($portfolioWebsite, $request);
    }

    public function handle(Customer|PortfolioWebsite $parent, ActionRequest $request): Response
    {
        $fields = [];

        $fields[] = [
            'title'  => '',
            'fields' => [
                'name' => [
                    'type'        => 'input',
                    'label'       => __('name'),
                    'placeholder' => __('Name for new announcement'),
                    'required'    => true,
                    'value'       => '',
                ],
            ]
        ];

        return Inertia::render(
            'CreateModel',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters()
                ),
                'title'       => __('new announcement'),
                'pageHead'    => [
                    'title'   => __('announcement'),
                    'actions' => [
                        [
                            'type'  => 'button',
                            'style' => 'cancel',
                            'route' => [
                                'name'       => preg_replace('/create$/', 'index', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters())
                            ]
                        ]
                    ]
                ],
                'formData'    => [
                    'blueprint' => $fields,
                    'route'     =>

                        match (class_basename($parent)) {
                            'Customer' => [
                                'name' => 'customer.models.banner.announcement.store'
                            ],
                            default => [
                                'name'       => 'customer.models.portfolio-website.banner.store',
                                'parameters' => [
                                    'portfolioWebsite' => $parent->id,
                                ]
                            ],
                        }
                ],
            ]
        );
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        return array_merge(
            IndexAnnouncement::make()->getBreadcrumbs(
                'customer.banners.banners.index',
                $routeParameters
            ),
            [
                [
                    'type'          => 'creatingModel',
                    'creatingModel' => [
                        'label' => __("creating banner"),
                    ]
                ]
            ]
        );
    }


}
