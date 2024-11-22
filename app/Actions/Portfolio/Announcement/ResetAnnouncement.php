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
use Illuminate\Support\Arr;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class ResetAnnouncement
{
    use AsAction;
    use WithAttributes;
    use WithActionUpdate;

    private bool $asAction = false;

    private Customer|PortfolioWebsite $parent;
    private string $scope;
    private Customer $customer;


    public function handle(Announcement $announcement): void
    {
        $this->update($announcement, [
            'fields'                  => Arr::get($announcement->liveSnapshot->layout, 'fields'),
            'container_properties'    => Arr::get($announcement->liveSnapshot->layout, 'container_properties'),
            'is_dirty'                => false
        ]);
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->get('customerUser')->hasPermissionTo("portfolio.banners.edit");
    }

    public function asController(PortfolioWebsite $portfolioWebsite, Announcement $announcement, ActionRequest $request): void
    {
        $this->scope    = 'portfolio-website';
        $this->parent   = $portfolioWebsite;

        $this->handle($announcement);
    }
}
