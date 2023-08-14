<?php
/*
 * Author: Artha <artha@aw-advantage.com>
 * Created: Thu, 08 Jun 2023 17:00:48 Central Indonesia Time, Sanur, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Drivers\Audits;

use App\Actions\Auth\User\LogUserRequest;
use App\Actions\Elasticsearch\BuildElasticsearchClient;
use App\Actions\Elasticsearch\IndexElasticsearchDocument;
use App\Enums\Elasticsearch\ElasticsearchTypeEnum;
use App\Models\Auth\User;
use App\Models\Tenancy\Tenant;
use Carbon\Carbon;
use Elastic\Elasticsearch\Client;
use Elastic\Transport\Exception\NoNodeAvailableException;
use Exception;
use hisorange\BrowserDetect\Parser as Browser;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Support\Facades\Config;
use OwenIt\Auditing\Contracts\Audit;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Contracts\AuditDriver;
use OwenIt\Auditing\Models\Audit as AuditModel;

class ElasticsearchAuditDriver implements AuditDriver
{
    /**
     * @var Client|null|NoNodeAvailableException
     */
    protected Client|null|NoNodeAvailableException  $client = null;

    /**
     * @var string|null
     */
    protected ?string $index = null;

    /**
     * @var string|null
     */
    protected ?string $type = null;

    /**
     * ElasticSearch constructor.
     */
    public function __construct()
    {


        $this->client = BuildElasticsearchClient::run();
        $this->type   = Config::get('audit.drivers.es.type', ElasticsearchTypeEnum::ACTION->value);
    }

    /**
     * Perform an audit.
     *
     * @param \OwenIt\Auditing\Contracts\Auditable $model
     *
     * @return \OwenIt\Auditing\Contracts\Audit
     * @throws \OwenIt\Auditing\Exceptions\AuditingException
     */
    public function audit(Auditable $model): Audit
    {
        $implementation = Config::get('audit.implementation', AuditModel::class);

        if(!app()->runningUnitTests()) {
            $this->storeAudit($model->toAudit());
        }

        return new $implementation();
    }


    /**
     * @throws \Elastic\Elasticsearch\Exception\ServerResponseException
     * @throws \Elastic\Elasticsearch\Exception\ClientResponseException
     */
    public function prune(Auditable $model): bool
    {
        if ($model->getAuditThreshold() > 0) {
            return $this->destroyAudit($model);
        }

        return false;
    }

    public function storeAudit($model): ?PendingDispatch
    {
        $model['created_at'] = Carbon::now()->toDateTimeString();

        return $this->indexAuditDocument($model);
    }

    /**
     * @throws \Elastic\Elasticsearch\Exception\ClientResponseException
     * @throws \Elastic\Elasticsearch\Exception\ServerResponseException
     */
    public function destroyAudit($model): bool
    {
        return $this->deleteAuditDocument($model);
    }


    public function syncWithSearchUsingQueue(): string
    {
        return config('audit.queue.queue');
    }

    /**
     * Get the queue connection that should be used when syncing.
     *
     * @return string
     */
    public function syncWithSearchUsing(): string
    {
        return config('audit.queue.connection') ?: config('queue.default');
    }

    public function indexAuditDocument($model)
    {
        $index=match($model['user_type']){
            'OrganisationUser'=>'_org',
            'PublicUser'=>'_public',
            'User'=>'_tenant_'.app('currentTenant')->slug
        };


        try {
            return IndexElasticsearchDocument::dispatch(
                config('elasticsearch.index_prefix').'audit_'.$index,
                $this->body($model),
                $this->type);
        } catch (Exception) {

        }
    }

    public function body($model): array
    {
        $parsedUserAgent = (new Browser())->parse($model['user_agent']);
        $user            = User::find($model['user_id']);

        $tenantSlug=null;
        if(Tenant::checkCurrent()){
            $tenantSlug=app('currentTenant')->slug;
        }

        return [
                'type'           => $this->type,
                'datetime'       => now(),
                'tenant'         => $tenantSlug,
                'route'          => $this->routes(),
                'module'         => explode('.', $this->routes()['name'])[0],
                'ip_address'     => request()->ip(),
                'location'       => json_encode((new LogUserRequest())->getLocation(request()->ip())),
                'user_agent'     => $model['user_agent'],
                'device_type'    => $parsedUserAgent->deviceType(),
                'platform'       => (new LogUserRequest())->detectWindows11($parsedUserAgent),
                'browser'        => $parsedUserAgent->browserName(),
                'old_values'     => $model['old_values'],
                'new_values'     => $model['new_values'],
                'event'          => $model['event'],
                'auditable_id'   => $model['auditable_id'],
                'auditable_type' => $model['auditable_type'],
                'user_id'        => $model['user_id'],
                'slug'           => $user->username,
                'user_name'      => $user->contact_name,
                'user_type'      => $model['user_type'],
                'tags'           => $model['tags'],
                'url'            => $model['url'],
            ];
    }

    public function routes(): array
    {
        return [
            'name'      => request()->route()->getName(),
            'arguments' => request()->route()->originalParameters(),
            'url'       => request()->path()
        ];
    }

    /**
     * @throws \Elastic\Elasticsearch\Exception\ServerResponseException
     * @throws \Elastic\Elasticsearch\Exception\ClientResponseException
     */
    public function searchAuditDocument($model)
    {
        $skip = $model->getAuditThreshold() - 1;

        $params = [
            'index' => $this->index,
            'type'  => $this->type,
            'size'  => 10000 - $skip,
            'from'  => $skip,
            'body'  => [
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                'term' => [
                                    'auditable_id' => $model->id
                                ]
                            ],
                            [
                                'term' => [
                                    'auditable_type' => $model->getMorphClass()
                                ]
                            ]
                        ]
                    ]
                ],
                'sort' => [
                    'created_at' => [
                        'order' => 'desc'
                    ]
                ]
            ]
        ];

        return $this->client->search($params);
    }

    /**
     * @throws \Elastic\Elasticsearch\Exception\ClientResponseException
     * @throws \Elastic\Elasticsearch\Exception\ServerResponseException
     */
    public function deleteAuditDocument($model): bool
    {
        $audits = $this->searchAuditDocument($model);
        $audits = $audits['hits']['hits'];

        if (count($audits)) {

            $params['body']=[];

            $audit_ids = array_column($audits, '_id');

            foreach ($audit_ids as $audit_id) {
                $params['body'][] = [
                    'delete' => [
                        '_index' => $this->index,
                        '_type'  => $this->type,
                        '_id'    => $audit_id
                    ]
                ];

            }

            return (bool) $this->client->bulk($params);
        }

        return false;
    }

    /**
     * @throws \Elastic\Elasticsearch\Exception\ClientResponseException
     * @throws \Elastic\Elasticsearch\Exception\ServerResponseException
     * @throws \Elastic\Elasticsearch\Exception\MissingParameterException
     */
    public function createIndex()
    {
        $params = [
            'index' => $this->index,
            'body'  => [
                'settings' => [
                    'number_of_shards'   => 3,
                    'number_of_replicas' => 0
                ]
            ]
        ];

        return $this->client->indices()->create($params);
    }

    /**
     * @throws \Elastic\Elasticsearch\Exception\ClientResponseException
     * @throws \Elastic\Elasticsearch\Exception\ServerResponseException
     */
    public function updateAliases()
    {
        $params['body'] = [
            'actions' => [
                [
                    'add' => [
                        'index' => $this->index,
                        'alias' => $this->index.'_write'
                    ]
                ]
            ]
        ];

        return $this->client->indices()->updateAliases($params);
    }

    /**
     * @throws \Elastic\Elasticsearch\Exception\ServerResponseException
     * @throws \Elastic\Elasticsearch\Exception\ClientResponseException
     * @throws \Elastic\Elasticsearch\Exception\MissingParameterException
     */
    public function deleteIndex()
    {
        $deleteParams = [
            'index' => $this->index
        ];

        return $this->client->indices()->delete($deleteParams);
    }

    /**
     * @throws \Elastic\Elasticsearch\Exception\ClientResponseException
     * @throws \Elastic\Elasticsearch\Exception\ServerResponseException
     * @throws \Elastic\Elasticsearch\Exception\MissingParameterException
     */
    public function existsIndex()
    {
        $params = [
            'index' => $this->index
        ];

        return $this->client->indices()->exists($params);
    }

    /**
     * @throws \Elastic\Elasticsearch\Exception\ServerResponseException
     * @throws \Elastic\Elasticsearch\Exception\ClientResponseException
     * @throws \Elastic\Elasticsearch\Exception\MissingParameterException
     */
    public function putMapping()
    {
        $params = [
            'index'        => $this->index,
            'type'         => $this->type,
            'need_restore' => $this->client instanceof Exception,
            'body'         => [
                $this->type => [
                    '_source' => [
                        'enabled' => true
                    ],
                    'properties' => [
                        'event' => [
                            'type'  => 'string',
                            'index' => 'not_analyzed'
                        ],
                        'auditable_type' => [
                            'type'  => 'string',
                            'index' => 'not_analyzed'
                        ],
                        'ip_address' => [
                            'type'  => 'string',
                            'index' => 'not_analyzed'
                        ],
                        'url' => [
                            'type'  => 'string',
                            'index' => 'not_analyzed'
                        ],
                        'user_agent' => [
                            'type'  => 'string',
                            'index' => 'not_analyzed'
                        ],
                        'created_at' => [
                            'type'   => 'date',
                            'format' => 'yyyy-MM-dd HH:mm:ss'
                        ],
                        'new_values' => [
                            'properties' => [
                                'created_at' => [
                                    'type'   => 'date',
                                    'format' => 'yyyy-MM-dd HH:mm:ss'
                                ],
                                'updated_at' => [
                                    'type'   => 'date',
                                    'format' => 'yyyy-MM-dd HH:mm:ss'
                                ],
                                'deleted_at' => [
                                    'type'   => 'date',
                                    'format' => 'yyyy-MM-dd HH:mm:ss'
                                ]
                            ]
                        ],
                        'old_values' => [
                            'properties' => [
                                'created_at' => [
                                    'type'   => 'date',
                                    'format' => 'yyyy-MM-dd HH:mm:ss'
                                ],
                                'updated_at' => [
                                    'type'   => 'date',
                                    'format' => 'yyyy-MM-dd HH:mm:ss'
                                ],
                                'deleted_at' => [
                                    'type'   => 'date',
                                    'format' => 'yyyy-MM-dd HH:mm:ss'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return $this->client->indices()->putMapping($params);
    }
}
