<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Announcement;

use App\Actions\Traits\WithActionUpdate;
use App\Enums\Portfolio\Announcement\AnnouncementStatusEnum;
use App\Models\Announcement;
use App\Models\Portfolio\PortfolioWebsite;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class ToggleAnnouncement
{
    use AsAction;
    use WithAttributes;
    use WithActionUpdate;

    public function handle(Announcement $announcement, string $status = null): void
    {
        $this->update($announcement, [
            'status' => $status
        ]);
    }

    public function asController(PortfolioWebsite $portfolioWebsite, Announcement $announcement): void
    {
        $status = $announcement->status === AnnouncementStatusEnum::ACTIVE ? AnnouncementStatusEnum::INACTIVE : AnnouncementStatusEnum::ACTIVE;

        $this->handle($announcement, $status->value);
    }
}
