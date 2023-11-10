<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 06 Nov 2023 10:49:43 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Leads\Prospect;

trait WithProspectPrepareForValidation
{
    public function prepareForValidation(): void
    {

        if ($this->get('contact_name', '') == '') {
            $this->fill(['contact_name' => null]);
        }

        if ($this->get('contact_name', '') == '') {
            $this->fill(['contact_name' => null]);
        }
        if ($this->get('company_name', '') == '') {
            $this->fill(['company_name' => null]);
        }

        if ($this->get('contact_website', '') == '') {
            $this->fill(['contact_website' => null]);
        }

        if ($this->get('phone', '') == '') {
            $this->fill(
                [
                    'phone' => null
                ]
            );
        }


        if ($this->get('contact_website')) {
            $this->fill(
                [
                    'contact_website' => 'https://'.$this->get('contact_website'),
                ]
            );
        }
    }

}
