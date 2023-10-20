<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioSocialAccount\PortfolioSocialAccountPost\UI;

use App\Actions\InertiaAction;
use App\Actions\Traits\Fields\WithPortfolioWebsiteFields;
use App\Models\Portfolio\PortfolioSocialAccount;
use App\Models\Portfolio\PortfolioSocialAccountPost;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowPortfolioSocialAccountPost extends InertiaAction
{
    use WithPortfolioWebsiteFields;

    public function authorize(ActionRequest $request): bool
    {
        return $request->get('customerUser')->hasPermissionTo('portfolio.edit');
    }


    public function asController(PortfolioSocialAccount $portfolioSocialAccount, PortfolioSocialAccountPost $post, ActionRequest $request): PortfolioSocialAccountPost
    {
        $this->initialisation($request);

        return $post;
    }


    public function htmlResponse(PortfolioSocialAccountPost $post, ActionRequest $request): \Illuminate\Http\Response|Response
    {
        $request->route()->getName();

        return Inertia::render(
            'Portfolio/PortfolioSocialAccountPost',
            [
                'breadcrumbs' => $this->getBreadcrumbs($post),
                'title'       => __('post'),
                'pageHead'    => [
                    'title'   => __("$post->task_name"),
                    'actions' => [
                        [
                            'type'  => 'button',
                            'style' => 'edit',
                            'label' => __('Edit Post'),
                            'route' => [
                                'name'       => 'customer.portfolio.social-accounts.post.edit',
                                'parameters' => array_values($this->originalParameters)
                            ],
                        ]
                    ]
                ],
            ]
        );
    }


    public function getBreadcrumbs(PortfolioSocialAccountPost $post): array
    {
        return array_merge(
            IndexPortfolioSocialAccountPosts::make()->getBreadcrumbs(
                'customer.portfolio.social-accounts.index',
                []
            ),
            [
                [
                    'type'   => 'simple',
                    'simple' => [
                        'route' => [
                            'name'       => 'customer.portfolio.social-accounts.show',
                            'parameters' => $this->originalParameters
                        ],
                        'label' => __($post->slug),
                    ]
                ]
            ]
        );
    }


}
