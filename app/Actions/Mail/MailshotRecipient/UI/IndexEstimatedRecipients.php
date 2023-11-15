<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 07 Oct 2023 21:20:25 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\MailshotRecipient\UI;

use App\Actions\Helpers\Query\BuildQuery;
use App\Actions\InertiaAction;
use App\Actions\Organisation\UI\CRM\ShowCRMDashboard;
use App\Http\Resources\CRM\AppointmentResource;
use App\Http\Resources\Mail\DispatchedEmailResource;
use App\Http\Resources\Mail\MailshotRecipientsResource;
use App\InertiaTable\InertiaTable;
use App\Models\Helpers\Query;
use App\Models\Mail\DispatchedEmail;
use App\Models\Mail\Mailshot;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexEstimatedRecipients extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->hasPermissionTo('crm.edit');

        return
            (
                $request->user()->hasPermissionTo('crm.view')
            );
    }

    /** @noinspection PhpUndefinedMethodInspection */
    public function handle(Mailshot $mailshot, $prefix = null): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->orWhere('customers.reference', '=', $value);
            });
        });

        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        $query = Query::find(Arr::get($mailshot->recipients_recipe, 'query_id'));
        $queryBuilder = BuildQuery::run($query);

        return $queryBuilder
            ->allowedFilters([$globalSearch])
            ->withPaginator($prefix)
            ->withQueryString();
    }

    public function jsonResponse(Mailshot $mailshot): AnonymousResourceCollection
    {
        return MailshotRecipientsResource::collection($this->handle($mailshot));
    }

    public function tableStructure(?array $modelOperations = null, $prefix = null, ?array $exportLinks = null): Closure
    {
        return function (InertiaTable $table) use ($modelOperations, $prefix, $exportLinks) {
            if ($prefix) {
                $table
                    ->name($prefix)
                    ->pageName($prefix . 'Page');
            }

            $table
                ->withModelOperations($modelOperations)
                ->withGlobalSearch()
                ->withEmptyState(
                    [
                        'title' => __('No estimated recipients found'),
                        'count' => 0
                    ]
                );
            if ($exportLinks) {
                $table->withExportLinks($exportLinks);
            }

            $table->column(key: 'contact_name', label: __('contact name'))
                ->column(key: 'email', label: __('email'))
                ->column(key: 'state', label: __('state'))
                ->defaultSort('email');
        };
    }

    public function asController(Mailshot $mailshot, ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request);

        return $this->handle($mailshot);
    }

    public function htmlResponse(LengthAwarePaginator $dispatchedEmails, ActionRequest $request): Response
    {
        return Inertia::render(
            'CRM/DispatchedEmails',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'title'       => __('estimated recipients'),
                'pageHead'    => [
                    'title'     => __('estimated recipients'),
                    'iconRight' => [
                        'icon'  => ['fal', 'fa-handshake'],
                        'title' => __('appointment')
                    ],
                    'actions'   =>
                        [
                            $this->canEdit ? [
                                'type'    => 'button',
                                'style'   => 'create',
                                'tooltip' => __('new appointment'),
                                'label'   => __('appointment'),
                                'route'   => [
                                    'name'       => 'org.crm.shop.dispatched.emails.create',
                                    'parameters' => array_values($this->originalParameters)
                                ]
                            ] : []
                        ]
                ],
                'data'        => AppointmentResource::collection($dispatchedEmails),

            ]
        );
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        $headCrumb = function (array $routeParameters = []) {
            return [
                [
                    'type'   => 'simple',
                    'simple' => [
                        'route' => $routeParameters,
                        'label' => __('estimated recipients'),
                        'icon'  => 'fal fa-bars'
                    ],
                ],
            ];
        };

        return match ($routeName) {
            'org.crm.estimated recipients.index' =>
            array_merge(
                (new ShowCRMDashboard())->getBreadcrumbs('org.crm.dashboard', $routeParameters),
                $headCrumb(
                    [
                        'name' => 'org.crm.estimated recipients.index',
                        null
                    ]
                ),
            ),
            'org.crm.shop.estimated recipients.index' =>
            array_merge(
                (new ShowCRMDashboard())->getBreadcrumbs('org.crm.shop.dashboard', $routeParameters),
                $headCrumb(
                    [
                        'name'       => 'org.crm.shop.estimated recipients.index',
                        'parameters' => $routeParameters
                    ]
                )
            ),
            default => []
        };
    }


}
