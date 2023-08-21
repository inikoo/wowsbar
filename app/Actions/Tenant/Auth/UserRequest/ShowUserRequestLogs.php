<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 17 Aug 2023 13:56:26 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Auth\UserRequest;

use App\Actions\Elasticsearch\BuildElasticsearchClient;
use App\Actions\Tenant\Auth\UserRequest\Traits\WithFormattedRequestLogs;
use App\InertiaTable\InertiaTable;
use Closure;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Lorisleiva\Actions\Concerns\AsObject;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ShowUserRequestLogs
{
    use AsObject;
    use WithFormattedRequestLogs;

    public function handle(string $query = null, $filter = 'VISIT'): LengthAwarePaginator|array|bool
    {
        $client = BuildElasticsearchClient::run();

        if ($client instanceof Client) {
            try {
                $params  = [
                    'index' => config('elasticsearch.index_prefix') . 'user_requests_' . app('currentTenant')->slug,
                    'size'  => 10000,
                    'body'  => [
                        'query' => [
                            'bool' => [
                                'must' => [
                                    ['match' => ['username' => $query]],
                                    ['match' => ['type' => $filter]]
                                ],
                            ],
                        ],
                    ],
                ];

                return $this->format($client, $params);

            } catch (ClientResponseException $e) {
                //dd($e->getMessage());
                // manage the 4xx error
                return false;
            } catch (ServerResponseException $e) {
                //dd($e->getMessage());
                // manage the 5xx error
                return false;
            } catch (Exception $e) {
                //dd($e->getMessage());
                // eg. network error like NoNodeAvailableException
                return false;
            } catch (NotFoundExceptionInterface|ContainerExceptionInterface $e) {
            }
        }

        return [];
    }

    public function tableStructure(): Closure
    {
        return function (InertiaTable $table) {
            $table
                ->withGlobalSearch()
                ->column(key: 'ip_address', label: __('IP Address'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'url', label: __('URL'), canBeHidden: false, sortable: true)
                ->column(key: 'module', label: __('Module'), canBeHidden: false, sortable: true)
                ->column(key: 'user_agent', label: __('User Agent'), canBeHidden: false, sortable: true)
                ->column(key: 'location', label: __('location'), canBeHidden: false)
                ->column(key: 'datetime', label: __('Date & Time'), canBeHidden: false, sortable: true)
                ->defaultSort('datetime');
            ;
        };
    }
}