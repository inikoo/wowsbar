<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Announcement;

use App\Actions\Helpers\Snapshot\StoreAnnouncementSnapshot;
use App\Models\Announcement;
use App\Models\CRM\Customer;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Symfony\Component\HttpFoundation\Response;

class StoreAnnouncement
{
    use AsAction;
    use WithAttributes;

    private bool $asAction = false;

    private Customer $parent;
    private string $scope;
    private Customer $customer;

    public $commandSignature = 'announcement:create {customer}';

    public function handle(Customer $parent, array $modelData): Announcement
    {
        $this->parent = $parent;

        data_set($modelData, 'ulid', Str::ulid());

        /** @var Announcement $announcement */
        $announcement = $parent->announcements()->create($modelData);

        $snapshot = StoreAnnouncementSnapshot::run(
            $announcement,
            [
                'layout' => [
                    'container_properties'  => null,
                    'fields'                => null
                ]
            ],
        );

        $announcement->update(
            [
                'unpublished_snapshot_id' => $snapshot->id
            ]
        );

        return $announcement;
    }

    public function htmlResponse(Announcement $announcement): Response
    {
        return Inertia::location(route('customer.banners.announcements.show', [
            'announcement' => $announcement->ulid
        ]));
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->get('customerUser')->hasPermissionTo("portfolio.banners.edit");
    }

    public function rules(): array
    {
        return [
            'name'                 => ['required', 'string', 'max:255']
        ];
    }

    public function inCustomer(ActionRequest $request): Announcement
    {
        $this->scope    = 'customer';
        $this->customer = $request->get('customer');

        $parent = customer();
        $request->validate();

        return $this->handle($parent, $request->validated());
    }

    public function asCommand(Command $command)
    {
        $customer = Customer::where('slug', $command->argument('customer'))->first();

        $this->handle($customer, [
            'name' => "Vika Announcement's"
        ]);
    }

    public function action(Customer $customer, array $objectData): Announcement
    {
        $this->customer = $customer;
        $this->asAction = true;
        $this->setRawAttributes($objectData);

        $validatedData = $this->validateAttributes();
        return $this->handle($customer, $validatedData);
    }
}
