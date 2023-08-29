<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 01 Jun 2023 15:06:28 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Firebase;

use Carbon\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsObject;

class CheckUserStatusFirebase
{
    use AsObject;
    use AsAction;

    public string $commandSignature = 'firebase:status';

    public function handle(): void
    {
        $database = app('firebase.database');
        $reference = $database->getReference('tenants');
        $values = $reference->getValue();

        foreach ($values as $key => $tenant) {
            $reference = $database->getReference('tenants/' . $key . '/active_users');
            $values = $reference->getValue();

            foreach ($values as $user => $value) {
                if (Carbon::make($value['last_active'])->timestamp <= now()->subMinutes(15)->timestamp) {
                    $database->getReference('tenants/' . $key . '/active_users/' . $user)->set(null);
                }
            }
        }
    }

    public function asCommand(): void
    {
        $this->handle();
    }
}
