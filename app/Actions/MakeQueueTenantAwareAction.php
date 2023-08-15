<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 12:37:36 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions;

use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Queue\Events\JobRetryRequested;
use Illuminate\Support\Arr;
use ReflectionClass;
use Spatie\Multitenancy\Actions\MakeQueueTenantAwareAction as BaseMakeQueueTenantAwareAction;
use Spatie\Multitenancy\Concerns\BindAsCurrentTenant;
use Spatie\Multitenancy\Exceptions\CurrentTenantCouldNotBeDeterminedInTenantAwareJob;
use Spatie\Multitenancy\Jobs\NotTenantAware;
use Spatie\Multitenancy\Jobs\TenantAware;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;
use Spatie\Multitenancy\Models\Tenant;

class MakeQueueTenantAwareAction extends BaseMakeQueueTenantAwareAction
{
    use UsesTenantModel;
    use BindAsCurrentTenant;

    public function execute(): void
    {
        $this
            ->listenForJobsBeingQueued()
            ->listenForJobsBeingProcessed()
            ->listenForJobsRetryRequested();

    }

    protected function listenForJobsBeingQueued(): static
    {
        app('queue')->createPayloadUsing(function ($connectionName, $queue, $payload) {


            $queueable = $payload['data']['command'];


            if (! $this->isTenantAware($queueable)) {

                return [];
            }

            return ['tenantId' => Tenant::current()?->id];
        });
        return $this;
    }

    protected function listenForJobsBeingProcessed(): static
    {

        app('events')->listen(JobProcessing::class, function (JobProcessing $event) {
            $this->bindOrForgetCurrentTenant($event);
        });

        return $this;
    }

    protected function listenForJobsRetryRequested(): static
    {
        app('events')->listen(JobRetryRequested::class, function (JobRetryRequested $event) {
            $this->bindOrForgetCurrentTenant($event);
        });

        return $this;
    }

    /**
     * @throws \ReflectionException
     */
    protected function isTenantAware(object $queueable): bool
    {
        $reflection = new ReflectionClass($this->getJobFromQueueable($queueable));

        if ($reflection->implementsInterface(TenantAware::class)) {
            return true;
        }

        if ($reflection->implementsInterface(NotTenantAware::class)) {
            return false;
        }

        if (in_array($reflection->name, config('multitenancy.tenant_aware_jobs'))) {
            return true;
        }

        if (in_array($reflection->name, config('multitenancy.not_tenant_aware_jobs'))) {
            return false;
        }

        return config('multitenancy.queues_are_tenant_aware_by_default') === true;
    }

    protected function getEventPayload($event): ?array
    {
        return match (true) {
            $event instanceof JobProcessing     => $event->job->payload(),
            $event instanceof JobRetryRequested => $event->payload(),
            default                             => null,
        };
    }

    /**
     * @throws \Spatie\Multitenancy\Exceptions\CurrentTenantCouldNotBeDeterminedInTenantAwareJob
     */
    protected function findTenant(JobProcessing|JobRetryRequested $event): Tenant
    {
        $tenantId = $this->getEventPayload($event)['tenantId'] ?? null;

        if (! $tenantId) {
            $event->job->delete();

            throw CurrentTenantCouldNotBeDeterminedInTenantAwareJob::noIdSet($event);
        }


        /** @var \Spatie\Multitenancy\Models\Tenant $tenant */
        if (! $tenant = $this->getTenantModel()::find($tenantId)) {
            $event->job->delete();

            throw CurrentTenantCouldNotBeDeterminedInTenantAwareJob::noTenantFound($event);
        }

        return $tenant;
    }

    protected function getJobFromQueueable(object $queueable)
    {
        $job = Arr::get(config('multitenancy.queueable_to_job'), $queueable::class);

        if (! $job) {
            return $queueable;
        }

        if (method_exists($queueable, $job)) {
            return $queueable->{$job}();
        }

        return $queueable->$job;
    }

    protected function bindOrForgetCurrentTenant(JobProcessing|JobRetryRequested $event): void
    {

        if (
            array_key_exists('tenantId', $this->getEventPayload($event))
            and $this->getEventPayload($event)['tenantId']
        ) {
            $this->bindAsCurrentTenant($this->findTenant($event)->makeCurrent());

            return;
        }

        $this->getTenantModel()::forgetCurrent();

    }
}
