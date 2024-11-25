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
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class ActivateAnnouncement
{
    use AsAction;
    use WithAttributes;
    use WithActionUpdate;

    public function handle(Announcement $announcement): void
    {
        $this->update($announcement, [
            'status' => AnnouncementStatusEnum::ACTIVE->value
        ]);
    }
}
