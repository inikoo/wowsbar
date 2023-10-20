<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioSocialAccount\PortfolioSocialAccountPost\UI;

use App\Actions\InertiaAction;
use App\Actions\Portfolio\PortfolioSocialAccount\UI\IndexPortfolioSocialAccounts;
use App\Actions\Traits\Fields\WithPortfolioWebsiteFields;
use App\Enums\Portfolio\PortfolioSocialAccount\PortfolioSocialAccountPostTypeEnum;
use App\Models\Portfolio\PortfolioSocialAccount;
use App\Models\Portfolio\PortfolioSocialAccountPost;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class EditPortfolioSocialAccountPost extends InertiaAction
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

        $sections['properties'] = [
            'label'  => __('Social account post properties'),
            'icon'   => 'fal fa-sliders-h',
            'fields' => [
                'task_name' => [
                    'type'     => 'input',
                    'label'    => __('task name'),
                    'required' => true,
                    'value'    => $post->task_name
                ],
                'start_at' => [
                    'type'     => 'date',
                    'label'    => __('upload at'),
                    'required' => true,
                    'value'    => $post->start_at
                ],
                'description' => [
                    'type'     => 'textarea',
                    'label'    => __('description'),
                    'required' => true,
                    'value'    => $post->description
                ],
                'notes' => [
                    'type'     => 'textarea',
                    'label'    => __('notes'),
                    'required' => true,
                    'value'    => $post->notes
                ],
            ]
        ];

        $currentSection = 'properties';
        if ($request->has('section') and Arr::has($sections, $request->get('section'))) {
            $currentSection = $request->get('section');
        }

        return Inertia::render(
            'EditModel',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'title'       => __('edit post'),
                'pageHead'    => [
                    'title'   => __('post'),
                    'actions' => [
                        [
                            'type'  => 'button',
                            'style' => 'cancel',
                            'label' => __('cancel'),
                            'route' => [
                                'name'       => 'customer.portfolio.social-accounts.show',
                                'parameters' => array_merge($request->route()->originalParameters(), ['tab' => PortfolioSocialAccountPostTypeEnum::POST->value])
                            ],
                        ]
                    ]
                ],
                'formData' => [
                    'current'   => $currentSection,
                    'blueprint' => $sections,
                    'args'      => [
                        'updateRoute' => [
                            'name'       => 'customer.models.portfolio-social-account.post.update',
                            'parameters' => $this->originalParameters
                        ],
                    ]
                ],
            ]
        );
    }


    public function getBreadcrumbs(): array
    {
        return array_merge(
            IndexPortfolioSocialAccounts::make()->getBreadcrumbs(
                'customer.portfolio.social-accounts.index',
                []
            ),
            [
                [
                    'type'          => 'creatingModel',
                    'creatingModel' => [
                        'label' => __("editing post"),
                    ]
                ]
            ]
        );
    }


}
