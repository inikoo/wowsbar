<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Dec 2023 11:52:05 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioWebsite;

use App\Models\CRM\Customer;
use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;
use Mockery\Exception;
use Spatie\QueryBuilder\QueryBuilder;

class GetPortfolioWebsites
{
    use AsAction;

    public function handle(?Customer $customer): Collection
    {
        $queryBuilder = QueryBuilder::for(PortfolioWebsite::class);
        if($customer) {
            $queryBuilder->where('customer_id', $customer->id);
        }
        return $queryBuilder->get();


    }


    public $commandSignature = 'portfolio-websites:list {--C|customer=}';

    public function asCommand(Command $command): int
    {

        $customer=null;
        if($command->option('customer')) {

            try {
                $customer=Customer::where('slug', $command->option('customer'))->firstOrFail();
            } catch (Exception) {
                $command->error("Customer not found");
            }

        }


        $portfolioWebsites=$this->handle($customer);



        $command->table(
            ['slug','customer', 'Url','integration'],
            $portfolioWebsites->map(
                function ($portfolioWebsite) {
                    return [
                        $portfolioWebsite->slug,
                        $portfolioWebsite->customer->slug    ,
                        $portfolioWebsite->url,
                        $portfolioWebsite->integration
                    ];
                }
            )
        );

        return 0;
    }

}
