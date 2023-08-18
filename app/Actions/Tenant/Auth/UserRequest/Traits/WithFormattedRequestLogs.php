<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 17 Aug 2023 13:56:26 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Auth\UserRequest\Traits;

use Elastic\Elasticsearch\Client;

trait WithFormattedRequestLogs
{
    /**
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Elastic\Elasticsearch\Exception\ClientResponseException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Elastic\Elasticsearch\Exception\ServerResponseException
     */
    public function format(Client $client, array $params)
    {
        try {
            $results = [];

            foreach (json_decode($client->search($params), true)['hits']['hits'] as $result) {
                $results[] = [
                    'username'      => $result['_source']['username'],
                    'ip_address'    => $result['_source']['ip_address'],
                    'location'      => json_decode($result['_source']['location'], true),
                    'device_type'   => $result['_source']['device_type'],
                    'module'        => $result['_source']['module'],
                    'platform'      => $result['_source']['platform'],
                    'browser'       => $result['_source']['browser'],
                    'route_name'    => $result['_source']['route']['name'],
                    'arguments'     => array_values($result['_source']['route']['arguments']),
                    'url'           => $result['_source']['route']['url'],
                    'datetime'      => $result['_source']['datetime']
                ];
            }

            return collect(array_reverse($results))->paginate(
                perPage: \request()->get('perPage') ?? config('ui.table.records_per_page')
            )->withQueryString();

        } catch (\Exception $e) {
            return [];
        }
    }
}
