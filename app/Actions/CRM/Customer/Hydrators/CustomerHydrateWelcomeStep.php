<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Customer\Hydrators;

use App\Models\CRM\Customer;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class CustomerHydrateWelcomeStep
{
    use AsAction;


    private int $step;
    private Customer $customer;

    public function init(Customer $customer): void
    {
        $this->customer = $customer;
        $this->step     = Arr::get($this->customer->data, 'welcome_step');
    }

    public function setStep(): void
    {
        $this->customer->update(
            [
                'data->welcome_step' => $this->step
            ]
        );
    }

    public function handle(Customer $customer): void
    {
        $this->init($customer);
    }

    public function websiteAdded(Customer $customer): void
    {
        $this->init($customer);

        if ($this->step == 1) {
            $this->step=2;
        }
        $this->setStep();
    }

    public function interestSet(Customer $customer): void
    {
        $this->init($customer);

        if ($this->step <=2) {
            $this->step=3;
        }
        $this->setStep();
    }

    public function isCustomer(Customer $customer): void
    {
        $this->init($customer);
        $this->step=4;
        $this->setStep();
    }



}
