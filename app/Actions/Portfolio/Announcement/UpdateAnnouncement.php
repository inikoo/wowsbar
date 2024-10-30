<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Announcement;

use App\Actions\Traits\WithActionUpdate;
use App\Models\Announcement;
use App\Models\CRM\Customer;
use App\Models\Portfolio\PortfolioWebsite;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class UpdateAnnouncement
{
    use AsAction;
    use WithAttributes;
    use WithActionUpdate;

    private bool $asAction = false;

    private Customer|PortfolioWebsite $parent;
    private string $scope;
    private Customer $customer;

    public function handle(Announcement $announcement, array $modelData): void
    {
        $snapshot = $announcement->unpublishedSnapshot;

        $snapshot->update(
            [
                'layout' => [
                    'container_properties'  => $modelData['container_properties'],
                    'fields'              => $modelData['fields']
                ]
            ]
        );

        $isDirty = true;
        if ($announcement->published_checksum == md5(json_encode($snapshot->layout))) {
            $isDirty = false;
        }

        $announcement->update(
            [
                'is_dirty' => $isDirty
            ]
        );

        $this->update($announcement, $modelData);
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
            'code'                 => ['sometimes', 'string'],
            'fields'               => ['sometimes', 'array'],
            'container_properties' => ['sometimes', 'array']
        ];
    }

    public function inCustomer(Announcement $announcement, ActionRequest $request): void
    {
        $this->scope    = 'customer';
        $this->customer = $request->get('customer');
        $request->validate();

        $this->handle($announcement, $request->validated());
    }
}
