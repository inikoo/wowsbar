<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 12 Oct 2023 08:56:03 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\SysAdmin\OrganisationUser;

use App\Actions\Elasticsearch\BuildElasticsearchClient;
use App\InertiaTable\InertiaTable;
use App\Models\Auth\OrganisationUser;
use App\Models\SysAdmin\Organisation;
use Closure;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Lorisleiva\Actions\Concerns\AsObject;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class IndexOrganisationUserRequestLogs
{
    use AsObject;

    public function handle(Organisation|OrganisationUser $parent, $prefix = null): LengthAwarePaginator|bool|array
    {





        $client = BuildElasticsearchClient::run();


        $query = [['match' => ['type' => 'VISIT']]];
        if (class_basename($parent) == 'OrganisationUser') {
            $query[] = ['match' => ['organisation_user_id' => $parent->id]];
        }
        if ($client instanceof Client) {
            try {
                $params = [
                    'index' => config('elasticsearch.index_prefix').'organisation_users_requests',
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
                        'username'               => $result['_source']['username'],
                        'organisation_user_id'   => $result['_source']['organisation_user_id'],
                        'ip_address'             => $result['_source']['ip_address'],
                        'location'               => json_decode($result['_source']['location'], true),
                        'device_type'            => $result['_source']['device_type'],
                        'module'                 => $result['_source']['module'],
                        'platform'               => $result['_source']['platform'],
                        'browser'                => $result['_source']['browser'],
                        'route_name'             => $result['_source']['route']['name'],
                        'arguments'              => array_values($result['_source']['route']['arguments']),
                        'url'                    => $result['_source']['route']['url'],
                        'datetime'               => $result['_source']['datetime']
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

    public function tableStructure(Organisation|OrganisationUser $parent, $prefix = null, ?array $exportLinks = null): Closure
    {
        return function (InertiaTable $table) use ($exportLinks, $parent, $prefix) {



            $table
                ->withGlobalSearch()
                ->withExportLinks($exportLinks);
            if (class_basename($parent) == 'Organisation') {
                $table->column(key: 'username', label: __('Username'), canBeHidden: false, sortable: true, searchable: true);
            }

            $table->column(key: 'url', label: __('URL'), canBeHidden: false, sortable: true)
                ->column(key: 'user_agent', label: __('device'), canBeHidden: false, sortable: true)
                ->column(key: 'location', label: __('location'), canBeHidden: false)
                ->column(key: 'datetime', label: __('Date & Time'), canBeHidden: false, sortable: true);
        };
    }
}
