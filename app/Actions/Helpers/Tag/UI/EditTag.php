<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\Tag\UI;

use App\Actions\InertiaAction;
use App\Actions\Portfolio\PortfolioWebsite\UI\ShowPortfolioWebsite;
use App\Models\Helpers\Tag;
use Exception;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class EditTag extends InertiaAction
{
    public function handle(Tag $tag): Tag
    {
        return $tag;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("portfolio.edit");

    }

    public function asController(Tag $tag, ActionRequest $request): Tag
    {
        $this->initialisation($request);

        return $this->handle($tag);
    }

    /**
     * @throws Exception
     */
    public function htmlResponse(Tag $tag, ActionRequest $request): Response
    {
        $sections['properties'] = [
            'label'  => __('tag properties'),
            'icon'   => 'fal fa-sliders-h',
            'fields' => [
                'name' => [
                    'type' => 'input',
                    'label' => __('name'),
                    'required' => true,
                    'value' => $tag->name
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
                'title'       => __("Tag"),
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters()
                ),
                'navigation' => [
                    'previous' => $this->getPrevious($tag, $request),
                    'next'     => $this->getNext($tag, $request),
                ],
                'pageHead' => [
                    'title' => $tag->name,
                    'icon'  => [
                        'title' => __('query'),
                        'icon'  => 'fal fa-globe'
                    ],


                    'iconRight' =>
                        [
                            'icon'  => ['fal', 'fa-edit'],
                            'title' => __("Editing tag")
                        ],

                    'actions' => [
                        [
                            'type'  => 'button',
                            'style' => 'exit',
                            'label' => __('Exit edit'),
                            'route' => [
                                'name'       => preg_replace('/edit$/', 'show', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters())
                            ]
                        ]
                    ],
                ],
                'formData' => [
                    'current'   => $currentSection,
                    'blueprint' => $sections,
                    'args'      => [
                        'updateRoute' => [
                            'name'       => 'org.models.tag.update',
                            'parameters' => $tag->slug
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

    public function getPrevious(Tag $tag, ActionRequest $request): ?array
    {
        $previous = Tag::where('slug', '<', $tag->slug)->orderBy('slug', 'desc')->first();

        return $this->getNavigation($previous, $request->route()->getName());
    }

    public function getNext(Tag $tag, ActionRequest $request): ?array
    {
        $next = Tag::where('slug', '>', $tag->slug)->orderBy('slug')->first();

        return $this->getNavigation($next, $request->route()->getName());
    }

    private function getNavigation(?Tag $tag, string $routeName): ?array
    {
        if (!$tag) {
            return null;
        }

        return match ($routeName) {
            'customer.portfolio.social-accounts.edit' => [
                'label' => $tag->name,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => [
                        'portfolioSocialAccount' => $tag->slug
                    ]
                ]
            ]
        };
    }
}
