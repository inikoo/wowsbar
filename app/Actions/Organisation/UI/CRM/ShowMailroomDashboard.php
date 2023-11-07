<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 11 Sep 2023 14:49:51 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\UI\CRM;

use App\Actions\InertiaAction;
use App\Actions\Leads\Prospect\UI\IndexProspectQueries;
use App\Actions\Organisation\UI\CRM\EmailTemplate\IndexEmailTemplates;
use App\Actions\UI\WithInertia;
use App\Enums\UI\Organisation\MailroomTabsEnum;
use App\Enums\UI\Organisation\ProspectsTabsEnum;
use App\Http\Resources\CRM\ProspectQueriesResource;
use App\Http\Resources\Mail\EmailTemplateResource;
use App\Models\Market\Shop;
use App\Models\Organisation\Organisation;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowMailroomDashboard extends InertiaAction
{
    use AsAction;
    use WithInertia;


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("crm.view");
    }

    public function handle(Organisation|Shop $parent): Organisation|Shop
    {
        return $parent;
    }

    public function asController(ActionRequest $request): Organisation
    {
        $this->initialisation($request)->withTab(MailroomTabsEnum::values());
        $request->validate();

        return organisation();
    }

    public function inShop(Shop $shop, ActionRequest $request): Shop
    {
        $this->initialisation($request)->withTab(MailroomTabsEnum::values());
        $request->validate();

        return $shop;
    }

    public function htmlResponse(Organisation|Shop $parent, ActionRequest $request): Response
    {
        $routeName       = $request->route()->getName();
        $routeParameters = $request->route()->originalParameters();

        return Inertia::render(
            'CRM/MailroomDashboard',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $routeName,
                    $routeParameters
                ),
                'title'       => 'CRM',
                'pageHead'    => [
                    'title' => __('mailroom'),
                    'icon'  => [
                        'title' => __('mailroom'),
                        'icon'  => 'fal fa-inbox-out'
                    ],
                ],
                'tabs' => [
                    'current'    => $this->tab,
                    'navigation' => MailroomTabsEnum::navigation()
                ],

                MailroomTabsEnum::EMAIL_TEMPLATE->value => $this->tab == MailroomTabsEnum::EMAIL_TEMPLATE->value ?
                    fn () => EmailTemplateResource::collection(IndexEmailTemplates::run())
                    : Inertia::lazy(fn () => EmailTemplateResource::collection(IndexEmailTemplates::run())),
            ]
        )->table(IndexEmailTemplates::make()->tableStructure(prefix: MailroomTabsEnum::EMAIL_TEMPLATE->value));
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters=[]): array
    {
        $headCrumb = function (array $routeParameters = []) {
            return [
                [
                    'type'   => 'simple',
                    'simple' => [
                        'route' => $routeParameters,
                        'label' => __('Mail'),
                        'icon'  => 'fal fa-inbox-out'
                    ],
                ],
            ];
        };

        return match ($routeName) {
            'org.crm.mailroom.dashboard' =>
            array_merge(
                (new ShowCRMDashboard())->getBreadcrumbs('org.crm.dashboard', $routeParameters),
                $headCrumb(
                    [
                        'name' => 'org.crm.mailroom.dashboard',
                        null
                    ]
                ),
            ),
            'org.crm.shop.mailroom.dashboard' =>
            array_merge(
                (new ShowCRMDashboard())->getBreadcrumbs('org.crm.shop.dashboard', $routeParameters),
                $headCrumb(
                    [
                        'name'       => 'org.crm.shop.mailroom.dashboard',
                        'parameters' => $routeParameters
                    ]
                )
            ),
            default => []
        };
    }

}
