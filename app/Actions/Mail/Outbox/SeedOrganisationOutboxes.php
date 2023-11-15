<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 14 Nov 2023 22:26:23 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\Outbox;

use App\Enums\Mail\Outbox\OutboxTypeEnum;
use App\Models\Mail\Mailroom;
use App\Models\Mail\Outbox;
use Lorisleiva\Actions\Concerns\AsAction;

class SeedOrganisationOutboxes
{
    use AsAction;

    public function handle(): void
    {
        foreach (OutboxTypeEnum::cases() as $case) {
            if ($case->scope() == 'organisation') {
                $mailroom = Mailroom::where('code', $case->mailroomCode()->value)->first();

                $outboxType = str($case->value)->camel()->kebab()->value();
                if (!Outbox::where('type', $outboxType)->exists()) {
                    StoreOutbox::run(
                        $mailroom,
                        [
                            'name'    => $case->label(),
                            'type'    => $outboxType,
                            'state'   => $case->defaultState()

                        ]
                    );
                }
            }
        }
    }

    public string $commandSignature = 'organisation:seed-outboxes';

    public function asCommand(): int
    {
        $this->handle();
        return 0;
    }


}
