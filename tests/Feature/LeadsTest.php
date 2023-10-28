<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 28 Oct 2023 11:56:42 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\Leads\Prospect\StoreProspect;
use App\Models\Leads\Prospect;
use App\Models\Market\Shop;

beforeAll(function () {
    loadDB('test_base_database.dump');
});


beforeEach(function () {
    createShop();
});


test('create prospect', function () {
    $shop      = Shop::first();
    $modelData = Prospect::factory()->definition();
    $prospect  = StoreProspect::make()->action($shop, $modelData);
    expect($prospect)->toBeInstanceOf(Prospect::class)
        ->and($shop->crmStats->number_prospects)->toBe(1)
        ->and($shop->crmStats->number_prospects_state_no_contacted)->toBe(1)
        ->and($shop->crmStats->number_prospects_state_contacted)->toBe(0)
        ->and($shop->crmStats->number_prospects_state_not_interested)->toBe(0)
        ->and($shop->crmStats->number_prospects_state_registered)->toBe(0)
        ->and($shop->crmStats->number_prospects_state_invoiced)->toBe(0)
        ->and($shop->crmStats->number_prospects_state_bounced)->toBe(0)

        ->and(organisation()->crmStats->number_prospects)->toBe(1)
        ->and(organisation()->crmStats->number_prospects_state_no_contacted)->toBe(1)
        ->and(organisation()->crmStats->number_prospects_state_contacted)->toBe(0)
        ->and(organisation()->crmStats->number_prospects_state_not_interested)->toBe(0)
        ->and(organisation()->crmStats->number_prospects_state_registered)->toBe(0)
        ->and(organisation()->crmStats->number_prospects_state_invoiced)->toBe(0)
        ->and(organisation()->crmStats->number_prospects_state_bounced)->toBe(0);

    return $prospect;
});
