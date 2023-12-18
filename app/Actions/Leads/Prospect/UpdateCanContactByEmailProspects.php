<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Dec 2023 11:30:14 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Leads\Prospect;

use App\Actions\Traits\WithCheckCanContactByEmail;
use App\Actions\Traits\WithImportModel;
use App\Models\Leads\Prospect;
use App\Models\Market\Shop;
use Exception;

class UpdateCanContactByEmailProspects
{
    use WithImportModel;
    use WithCheckCanContactByEmail;

    public function handle(Prospect $prospect): Prospect
    {
        $isValidEmail = true;
        if ($prospect->email != '' && !filter_var($prospect->email, FILTER_VALIDATE_EMAIL)) {
            $isValidEmail = false;
        }
        $prospect->update([
            'is_valid_email' => $isValidEmail
        ]);


        $prospect->update([
            'can_contact_by_email' => $this->canContactProspectByEmail($prospect)
        ]);

        return $prospect;
    }


    public string $commandSignature = 'prospects:can_contact_by_email {shop}';

    public function asCommand($command): int
    {
        try {
            $shop = Shop::where('slug', $command->argument('shop'))->firstOrFail();
        } catch (Exception) {
            $command->error('Shop not found');
            exit;
        }

        $prospects = Prospect::where('shop_id', $shop->id)->get();
        $prospects->each(function ($prospect) use ($command) {



            $prospect = $this->handle($prospect);
            if ($prospect->wasChanged()) {
                print_r($prospect->wasChanged());
                $command->info($prospect->email.' '.$prospect->can_contact_by_email);
            }
        });


        return 0;
    }


}
