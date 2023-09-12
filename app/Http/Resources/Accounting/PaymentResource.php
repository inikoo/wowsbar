<?php
/*
 *  Author: Jonathan lopez <raul@inikoo.com>
 *  Created: Sat, 22 Oct 2022 18:53:15 British Summer Time, Sheffield, UK
 *  Copyright (c) 2022, inikoo
 */

namespace App\Http\Resources\Accounting;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string $status
 * @property string $date
 * @property int $data
 * @property string $slug
 * @property string $reference
 * @property mixed $created_at
 * @property mixed $updated_at
 * @property string $payment_service_providers_slug
 * @property string $payment_accounts_slug
 *
 */
class PaymentResource extends JsonResource
{
    public function toArray($request): array
    {
        return array(
            'slug'                           => $this->slug,
            'payment_service_providers_slug' => $this->payment_service_providers_slug,
            'payment_accounts_slug'          => $this->payment_accounts_slug,
            'status'                         => $this->status,
            'date'                           => $this->date,
            'reference'                      => $this->reference,
            'created_at'                     => $this->created_at,
            'updated_at'                     => $this->updated_at,
        );
    }
}
