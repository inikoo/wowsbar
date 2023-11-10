<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 09 Nov 2023 18:55:58 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Traits;

use App\Models\CRM\Customer;
use App\Models\Leads\Prospect;

trait WithCheckCanSendEmail
{
    protected function canSend(Prospect|Customer $recipient): bool
    {
        return match (class_basename($recipient)) {
            'Prospect' => $this->canSendProspect($recipient),
            'Customer' => $this->canSendCustomer($recipient)
        };
    }

    protected function canSendCustomer(Customer $customer): bool
    {
        return false;
    }

    protected function canSendProspect(Prospect $prospect): bool
    {
        if ($prospect->dont_contact_me) {
            return false;
        }

        if (!filter_var($prospect->email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }

}
