<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Announcement;

use App\Actions\Helpers\Snapshot\StoreWebpageSnapshot;
use App\Models\Announcement;
use App\Models\CRM\Customer;
use App\Models\Portfolio\Banner;
use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreAnnouncement
{
    use AsAction;
    use WithAttributes;

    private bool $asAction = false;

    private Customer|PortfolioWebsite $parent;
    private string $scope;
    private Customer $customer;


    public function handle(Customer|PortfolioWebsite $parent, array $modelData): Announcement
    {
        $this->parent = $parent;

        data_set($modelData, 'ulid', Str::ulid());

        $announcement = Announcement::create($modelData);

        $snapshot = StoreWebpageSnapshot::run(
        $announcement,
        [
            'layout' => [
                'channel_properties'  => '',
                'fields'              => ''
            ]
        ],
        );

        $announcement->update(
            [
                'unpublished_snapshot_id' => $snapshot->id,
                'fields'                  => Arr::get($snapshot->layout, 'fields'),
                'container_properties'    => Arr::get($snapshot->layout, 'container_properties'),
            ]
        );
        return $announcement;
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
            'code'                 => ['required', 'string'],
            'name'                 => ['required', 'string', 'max:255'],
            'icon'                 => ['required', 'string'],
            'fields'               => ['required', 'array'],
            'container_properties' => ['required', 'array']
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

    public function action(PortfolioWebsite $portfolioWebsite, array $objectData): Announcement
    {
        $this->customer = $portfolioWebsite->customer;
        data_set($objectData, 'portfolio_website_id', $portfolioWebsite->id);
        $this->asAction = true;
        $this->setRawAttributes($objectData);

        $validatedData = $this->validateAttributes();
        return $this->handle($portfolioWebsite, $validatedData);
    }
}
