<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 28 Oct 2023 11:56:42 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\Leads\Prospect\StoreProspect;
use App\Actions\Mail\Mailshot\StoreMailshot;
use App\Enums\Mail\MailshotTypeEnum;
use App\Models\Leads\Prospect;
use App\Models\Mail\Mailshot;
use Inertia\Testing\AssertableInertia;

use function Pest\Laravel\{get};
use function Pest\Laravel\{actingAs};

beforeAll(function () {
    loadDB('test_base_database.dump');
});


beforeEach(function () {
    list(
        $this->organisation,
        $this->organisationUser,
        $this->shop
    ) = createShop();

    Config::set(
        'inertia.testing.page_paths',
        [resource_path('js/Pages/Organisation')]
    );
});


test('create prospect', function () {
    $shop      = $this->shop;
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

test('create 2nd prospect', function () {
    $shop         = $this->shop;
    $organisation = $this->organisation;
    $modelData    = Prospect::factory()->definition();
    $prospect     = StoreProspect::make()->action($shop, $modelData);
    expect($prospect)->toBeInstanceOf(Prospect::class)
        ->and($shop->crmStats->number_prospects)->toBe(2)
        ->and($shop->crmStats->number_prospects_state_no_contacted)->toBe(2)
        ->and($shop->crmStats->number_prospects_state_contacted)->toBe(0)
        ->and($shop->crmStats->number_prospects_state_not_interested)->toBe(0)
        ->and($shop->crmStats->number_prospects_state_registered)->toBe(0)
        ->and($shop->crmStats->number_prospects_state_invoiced)->toBe(0)
        ->and($shop->crmStats->number_prospects_state_bounced)->toBe(0)
        ->and($organisation->crmStats->number_prospects)->toBe(2)
        ->and($organisation->crmStats->number_prospects_state_no_contacted)->toBe(2)
        ->and($organisation->crmStats->number_prospects_state_contacted)->toBe(0)
        ->and($organisation->crmStats->number_prospects_state_not_interested)->toBe(0)
        ->and($organisation->crmStats->number_prospects_state_registered)->toBe(0)
        ->and($organisation->crmStats->number_prospects_state_invoiced)->toBe(0)
        ->and($organisation->crmStats->number_prospects_state_bounced)->toBe(0);

    return $prospect;
});


test('create prospect mailshot', function () {
    $shop         = $this->shop;
    $organisation = $this->organisation;
    $dataModel    = [
        'subject' => 'hello',
        'type'    => MailshotTypeEnum::PROSPECT_MAILSHOT
    ];
    $mailshot     = StoreMailshot::make()->action($shop, $dataModel);
    expect($mailshot)->toBeInstanceOf(Mailshot::class)
        ->and($organisation->mailStats->number_mailshots)->toBe(1)
        ->and($organisation->mailStats->number_mailshots_type_prospect_mailshot)->toBe(1)
        ->and($organisation->mailStats->number_mailshots_state_in_process)->toBe(1)
        ->and($organisation->mailStats->number_mailshots_type_prospect_mailshot_state_in_process)->toBe(1)
        ->and($shop->mailStats->number_mailshots)->toBe(1)
        ->and($shop->mailStats->number_mailshots_type_prospect_mailshot)->toBe(1)
        ->and($shop->mailStats->number_mailshots_state_in_process)->toBe(1)
        ->and($shop->mailStats->number_mailshots_type_prospect_mailshot_state_in_process)->toBe(1);
});

test('can show list of prospects', function () {
    Config::set(
        'page_paths',
        resource_path('js/Pages/Organisation')
    );
    $shop     = $this->shop;
    $response = actingAs($this->organisationUser, 'org')->get(route('org.crm.shop.prospects.index', [$shop->slug]));
    $response->assertInertia(function (AssertableInertia $page) {
        $page
            ->component('CRM/Prospects')
            ->has('title');
    });
});
