<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Market\Shop\UI;

use App\Actions\Assets\Language\UI\GetLanguagesOptions;
use App\Actions\InertiaAction;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

/**
 * @property array $breadcrumbs
 * @property bool $canEdit
 * @property string $title
 */
class CreateShops extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->hasPermissionTo('shops');

        return
            (
                $request->user()->tokenCan('root') or
                $request->user()->hasPermissionTo('shops.edit')
            );
    }

    public function asController(ActionRequest $request): ActionRequest
    {
        return $request;
    }


    public function htmlResponse(ActionRequest $request): Response
    {

        return Inertia::render(
            'CreateModelBySpreadSheet',
            [
                'title'            => __('shops'),
                'documentName'     => 'tes',
                'pageHead'         => [
                    'title'    => __('Upload shops'),
                    'exitEdit' => [
                        'label' => __('Back'),
                        'route' => [
                            'name'       => 'org.shops.index',
                            'parameters' => array_values($this->originalParameters)
                        ],
                    ],
                    'clearMulti' => [
                        'route' => [
                            'name'       => 'org.shops.create-multi-clear',
                            'parameters' => array_values($this->originalParameters)
                        ],
                    ]
                ],
                'sheetData' => [
                    'columns' => [
                        [
                            'id'             => 'code',
                            'name'           => __('Code'),
                            'columnType'     => 'string',
                            'prop'           => 'code',
                            'required'       => true,
                        ],
                        [
                            'id'             => 'name',
                            'name'           => __('Label'),
                            'columnType'     => 'string',
                            'prop'           => 'name',
                            'required'       => true,
                        ],
                        [
                            'id'             => 'type',
                            'name'           => __('type'),
                            'columnType'     => 'select',
                            'prop'           => 'type',
                            'required'       => true,
                            'options'        => GetLanguagesOptions::make()->all(),
                        ],
                        [
                            'id'             => 'hidden',
                            'name'           => __('hidden'),
                            'columnType'     => 'string',
                            'prop'           => 'hidden',
                            'required'       => true,
                            'hidden'         => true
                        ],
                    ],
                ],
                'saveRoute' => [
                    'name' => 'org.models.shop.store-multi',
                ]

            ]
        );
    }


}
