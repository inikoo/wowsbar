<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 11 Oct 2023 17:06:28 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Auth\CustomerUser\UI;

use App\Actions\Elasticsearch\BuildElasticsearchClient;
use App\InertiaTable\InertiaTable;
use App\Models\Auth\CustomerUser;
use App\Models\CRM\Customer;
use Closure;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Lorisleiva\Actions\Concerns\AsObject;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class IndexCustomerUserRequestLogs
{
    use AsObject;

    public function handle(Customer|CustomerUser $parent): LengthAwarePaginator|bool|array
    {
        $client = BuildElasticsearchClient::run();


        $query = [['match' => ['type' => 'VISIT']]];
        if (class_basename($parent) == 'CustomerUser') {
            $query[] = ['match' => ['customer_user_id' => $parent->id]];
        } elseif (class_basename($parent) == 'Customer') {
            $query[] = ['match' => ['customer_id' => $parent->id]];
        }

        if ($client instanceof Client) {
            try {
                $params = [
                    'index' => config('elasticsearch.index_prefix').'customer_users_requests',
                    'size'  => request()->get('perPage') ?? config('ui.table.records_per_page'),
                    'body'  => [
                        'query' => [
                            'bool' => [
                                'must' => $query
                            ],
                        ],
                    ]
                ];


                $results = [];

                foreach (json_decode($client->search($params), true)['hits']['hits'] as $result) {
                    $results[] = [
                        'username'           => $result['_source']['username'],
                        'customer_user_slug' => $result['_source']['customer_user_slug'],
                        'customer_user_id'   => $result['_source']['customer_user_id'],
                        'ip_address'         => $result['_source']['ip_address'],
                        'location'           => json_decode($result['_source']['location'], true),
                        'device_type'        => $result['_source']['device_type'],
                        'module'             => $result['_source']['module'],
                        'platform'           => $result['_source']['platform'],
                        'browser'            => $result['_source']['browser'],
                        'route_name'         => $result['_source']['route']['name'],
                        'arguments'          => array_values($result['_source']['route']['arguments']),
                        'url'                => $result['_source']['route']['url'],
                        'datetime'           => $result['_source']['datetime']
                    ];
                }


                return collect(array_reverse($results))->paginate(
                    perPage: request()->get('perPage') ?? config('ui.table.records_per_page')
                )->withQueryString();
            } catch (ClientResponseException) {
                //dd($e->getMessage());
                // todo manage the 4xx error
                return [];
            } catch (ServerResponseException) {
                //dd($e->getMessage());
                // todo manage the 5xx error
                return [];
            } catch (Exception) {
                //dd($e->getMessage());
                // todo eg. network error like NoNodeAvailableException
                return [];
            } catch (NotFoundExceptionInterface|ContainerExceptionInterface) {
            }
        }

        return [];
    }

    public function tableStructure(CustomerUser|Customer $parent, ?array $exportLinks = null): Closure
    {
        return function (InertiaTable $table) use ($exportLinks, $parent) {
            $table
                ->withGlobalSearch()
                ->withExportLinks($exportLinks);
            if (class_basename($parent) == 'Customer') {
                $table->column(key: 'customer_user_slug', label: __('User'), canBeHidden: false, sortable: true, searchable: true);
            }

            $table->column(key: 'url', label: __('URL'), canBeHidden: false, sortable: true)
                ->column(key: 'user_agent', label: __('device'), canBeHidden: false, sortable: true)
                ->column(key: 'location', label: __('location'), canBeHidden: false)
                ->column(key: 'datetime', label: __('Date & Time'), canBeHidden: false, sortable: true);
        };
    }
}
