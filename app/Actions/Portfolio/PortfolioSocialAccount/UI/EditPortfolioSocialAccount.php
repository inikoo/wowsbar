<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioSocialAccount\UI;

use App\Actions\InertiaAction;
use App\Actions\Portfolio\PortfolioWebsite\UI\GetPortfolioWebsitesOptions;
use App\Actions\Portfolio\PortfolioWebsite\UI\ShowPortfolioWebsite;
use App\Enums\Portfolio\PortfolioSocialAccount\PortfolioSocialAccountPlatformEnum;
use App\Models\Portfolio\PortfolioSocialAccount;
use Exception;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\LaravelOptions\Options;

class EditPortfolioSocialAccount extends InertiaAction
{
    public function handle(PortfolioSocialAccount $portfolioSocialAccount): PortfolioSocialAccount
    {
        return $portfolioSocialAccount;
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->get('customerUser')->hasPermissionTo('portfolio.edit');
        return $request->get('customerUser')->hasPermissionTo("portfolio.edit");

    }

    public function asController(PortfolioSocialAccount $portfolioSocialAccount, ActionRequest $request): PortfolioSocialAccount
    {
        $this->initialisation($request);

        return $this->handle($portfolioSocialAccount);
    }

    /**
     * @throws Exception
     */
    public function htmlResponse(PortfolioSocialAccount $portfolioSocialAccount, ActionRequest $request): Response
    {
        $sections['properties'] = [
            'label'  => __('Social account properties'),
            'icon'   => 'fal fa-sliders-h',
            'fields' => [
                'provider' => [
                    'type' => 'select',
                    'label' => __('provider'),
                    'placeholder' => 'Select Account Provider',
                    'options' => Options::forEnum(PortfolioSocialAccountPlatformEnum::class)->toArray(),
                    'required' => true,
                    'value' => $portfolioSocialAccount->platform,
                    'mode' => 'single'
                ],
                'username' => [
                    'type' => 'input',
                    'label' => __('username'),
                    'required' => true,
                    'value' => $portfolioSocialAccount->username
                ],
                'url' => [
                    'type' => 'input',
                    'label' => __('profile url'),
                    'required' => true,
                    'value' => $portfolioSocialAccount->url
                ]
            ]
        ];

        $currentSection = 'properties';
        if ($request->has('section') and Arr::has($sections, $request->get('section'))) {
            $currentSection = $request->get('section');
        }

        return Inertia::render(
            'EditModel',
            [
                'title' => __("Social Account"),
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters()
                ),
                'navigation' => [
                    'previous' => $this->getPrevious($portfolioSocialAccount, $request),
                    'next' => $this->getNext($portfolioSocialAccount, $request),
                ],
                'pageHead' => [
                    'title' => $portfolioSocialAccount->username,
                    'icon' => [
                        'title' => __('social account'),
                        'icon' => 'fal fa-globe'
                    ],


                    'iconRight' =>
                        [
                            'icon' => ['fal', 'fa-edit'],
                            'title' => __("Editing social account")
                        ],

                    'actions' => [
                        [
                            'type' => 'button',
                            'style' => 'exit',
                            'label' => __('Exit edit'),
                            'route' => [
                                'name' => preg_replace('/edit$/', 'show', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters())
                            ]
                        ]
                    ],
                ],
                'formData' => [
                    'current'   => $currentSection,
                    'blueprint' => $sections,
                    'args' => [
                        'updateRoute' => [
                            'name' => 'customer.models.portfolio-social-account.update',
                            'parameters' => $portfolioSocialAccount->slug
                        ],
                    ]
                ],

            ]
        );
    }


    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        return ShowPortfolioWebsite::make()->getBreadcrumbs(
            $routeName,
            $routeParameters,
            suffix: '(' . __('editing') . ')'
        );
    }

    public function getPrevious(PortfolioSocialAccount $portfolioSocialAccount, ActionRequest $request): ?array
    {
        $previous = PortfolioSocialAccount::where('slug', '<', $portfolioSocialAccount->slug)->orderBy('slug', 'desc')->first();

        return $this->getNavigation($previous, $request->route()->getName());
    }

    public function getNext(PortfolioSocialAccount $portfolioSocialAccount, ActionRequest $request): ?array
    {
        $next = PortfolioSocialAccount::where('slug', '>', $portfolioSocialAccount->slug)->orderBy('slug')->first();

        return $this->getNavigation($next, $request->route()->getName());
    }

    private function getNavigation(?PortfolioSocialAccount $portfolioSocialAccount, string $routeName): ?array
    {
        if (!$portfolioSocialAccount) {
            return null;
        }

        return match ($routeName) {
            'customer.portfolio.social-accounts.edit' => [
                'label' => $portfolioSocialAccount->username,
                'route' => [
                    'name' => $routeName,
                    'parameters' => [
                        'portfolioSocialAccount' => $portfolioSocialAccount->slug
                    ]
                ]
            ]
        };
    }
}
