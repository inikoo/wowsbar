<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 11 Sep 2023 14:36:26 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\CRM\Appointment\UI\CreateAppointment;
use App\Actions\CRM\Appointment\UI\EditAppointment;
use App\Actions\CRM\Appointment\UI\IndexAppointments;
use App\Actions\CRM\Appointment\UI\ShowAppointment;
use App\Actions\CRM\Customer\UI\CreateCustomer;
use App\Actions\CRM\Customer\UI\EditCustomer;
use App\Actions\CRM\Customer\UI\IndexCustomers;
use App\Actions\CRM\Customer\UI\RemoveCustomer;
use App\Actions\CRM\Customer\UI\ShowCustomer;
use App\Actions\CRM\User\UI\CreateOrgCustomerUser;
use App\Actions\CRM\User\UI\EditOrgCustomerUser;
use App\Actions\CRM\User\UI\IndexOrgCustomerUsers;
use App\Actions\CRM\User\UI\ShowOrgCustomerUser;
use App\Actions\Leads\Prospect\ExportProspects;
use App\Actions\Leads\Prospect\Mailshots\UI\CreateProspectsMailshot;
use App\Actions\Leads\Prospect\Mailshots\UI\IndexProspectMailshots;
use App\Actions\Leads\Prospect\Queries\UI\CreateProspectQuery;
use App\Actions\Leads\Prospect\Queries\UI\EditProspectQuery;
use App\Actions\Leads\Prospect\Queries\UI\IndexProspectQueries;
use App\Actions\Leads\Prospect\Queries\UI\ShowProspectQuery;
use App\Actions\Leads\Prospect\RemoveProspect;
use App\Actions\Leads\Prospect\Tags\UI\CreateProspectTag;
use App\Actions\Leads\Prospect\Tags\UI\EditProspectTag;
use App\Actions\Leads\Prospect\Tags\UI\IndexProspectTags;
use App\Actions\Leads\Prospect\Tags\UI\ShowProspectTag;
use App\Actions\Leads\Prospect\UI\CreateProspect;
use App\Actions\Leads\Prospect\UI\EditProspect;
use App\Actions\Leads\Prospect\UI\IndexProspects;
use App\Actions\Leads\Prospect\UI\ShowProspect;
use App\Actions\Mail\EmailTemplate\UI\ShowEmailTemplate;
use App\Actions\Mail\EmailTemplate\UI\ShowEmailTemplateWorkshop;
use App\Actions\Mail\Mailshot\UI\EditProspectMailshot;
use App\Actions\Mail\Mailshot\UI\ShowProspectMailshot;
use App\Actions\Mail\Mailshot\UI\ShowProspectMailshotWorkshop;
use App\Actions\Organisation\UI\CRM\ShowCRMDashboard;
use App\Actions\Organisation\UI\CRM\ShowMailroomDashboard;
use App\Actions\Subscriptions\CustomerSocialAccount\UI\ShowCustomerSocialAccount;
use App\Actions\Subscriptions\CustomerWebsite\UI\CreateCustomerWebsite;
use App\Actions\Subscriptions\CustomerWebsite\UI\EditCustomerWebsite;
use App\Actions\Subscriptions\CustomerWebsite\UI\IndexCustomerWebsites;
use App\Actions\Subscriptions\CustomerWebsite\UI\ShowCustomerWebsite;

Route::get('/', function () {
    return redirect('/crm/dashboard');
});
Route::get('/dashboard', [ShowCRMDashboard::class, 'inOrganisation'])->name('dashboard');

Route::get('customers', IndexCustomers::class)->name('customers.index');
Route::get('customers/create', CreateCustomer::class)->name('customers.create');

Route::prefix('customers/{customer}')->as('customers.')->group(function () {
    Route::get('', ShowCustomer::class)->name('show');
    Route::get('edit', [EditCustomer::class, 'inOrganisation'])->name('edit');
    Route::get('delete', RemoveCustomer::class)->name('remove');
    Route::get('customer-users', [IndexOrgCustomerUsers::class, 'inCustomer'])->name('show.customer-users.index');
    Route::get('customer-users/create', [CreateOrgCustomerUser::class, 'inCustomer'])->name('show.customer-users.create');
    Route::get('customer-users/{user}', [ShowOrgCustomerUser::class, 'inCustomer'])->name('show.customer-users.show');
    Route::get('customer-users/{user}/edit', [EditOrgCustomerUser::class, 'inCustomer'])->name('show.customer-users.edit');
});

Route::prefix('prospects')->as('prospects.')->group(function () {
    Route::get('/', IndexProspects::class)->name('index');
    Route::get('/mailshots', [IndexProspectMailshots::class, 'inShop'])->name('mailshots.index');

    Route::get('/{prospect}', IndexProspects::class)->name('show');
    Route::get('/{prospect}/delete', RemoveProspect::class)->name('remove');
});

Route::prefix('shop/{shop}')->as('shop.')->group(function () {
    Route::get('/', function ($shop) {
        return redirect()->route('org.crm.shop.dashboard', [$shop]);
    });
    Route::get('/dashboard', [ShowCRMDashboard::class, 'inShop'])->name('dashboard');

    Route::get('customers', [IndexCustomers::class, 'inShop'])->name('customers.index');
    Route::get('customers/create', [CreateCustomer::class, 'inShop'])->name('customers.create');

    Route::prefix('customers/{customer}')->as('customers.')->group(function () {
        Route::get('', [ShowCustomer::class, 'inShop'])->name('show');
        Route::get('/edit', [EditCustomer::class, 'inShop'])->name('edit');

        Route::get('/social-accounts/{customerSocialAccount}', [ShowCustomerSocialAccount::class, 'inCustomer'])->name('show.customer-social-accounts.show');

        Route::get('/users', [IndexOrgCustomerUsers::class, 'inCustomerInShop'])->name('show.customer-users.index');
        Route::get('/users/create', [CreateOrgCustomerUser::class, 'inCustomerInShop'])->name('show.customer-users.create');
        Route::get('/users/{customerUser}', [ShowOrgCustomerUser::class, 'inCustomerInShop'])->name('show.customer-users.show');
        Route::get('users/{customerUser}/edit', [EditOrgCustomerUser::class, 'inCustomerInShop'])->name('show.customer-users.edit');

        Route::get('/customer-websites', [IndexCustomerWebsites::class, 'inCustomerInShop'])->name('show.customer-websites.index');
        Route::get('/customer-websites/create', [CreateCustomerWebsite::class, 'inCustomerInShop'])->name('show.customer-websites.create');
        Route::get('/customer-websites/{customerWebsite}', [ShowCustomerWebsite::class, 'inCustomerInShop'])->name('show.customer-websites.show');
        Route::get('/customer-websites/{customerWebsite}/edit', [EditCustomerWebsite::class, 'inCustomerInShop'])->name('show.customer-websites.edit');
    });


    Route::prefix('prospects')->as('prospects.')->group(function () {
        Route::get('/', [IndexProspects::class, 'inShop'])->name('index');
        Route::get('/create', [CreateProspect::class, 'inShop'])->name('create');

        Route::get('/export', ExportProspects::class)->name('export');

        Route::prefix('lists')->as('lists.')->group(function () {
            Route::get('/', [IndexProspectQueries::class, 'inShop'])->name('index');
            Route::get('/create', [CreateProspectQuery::class, 'inShop'])->name('create');
            Route::get('/{query}/edit', [EditProspectQuery::class, 'inShop'])->name('edit');
            Route::get('{query}', ShowProspectQuery::class)->name('show');
        });

        Route::prefix('tags')->as('tags.')->group(function () {
            Route::get('/', [IndexProspectTags::class, 'inShop'])->name('index');
            Route::get('/create', [CreateProspectTag::class, 'inShop'])->name('create');
            Route::get('{tag}/edit', [EditProspectTag::class, 'inShop'])->name('edit');
            Route::get('{tag}', [ShowProspectTag::class, 'inShop'])->name('show');
        });

        Route::prefix('mailshots')->as('mailshots.')->group(function () {
            Route::get('', [IndexProspectMailshots::class, 'inShop'])->name('index');
            Route::get('create', [CreateProspectsMailshot::class, 'inShop'])->name('create');
            Route::get('{mailshot}/edit', EditProspectMailshot::class)->name('edit');
            Route::get('{mailshot}/workshop', ShowProspectMailshotWorkshop::class)->name('workshop');
            Route::get('{mailshot}', ShowProspectMailshot::class)->name('show');
        });

        Route::get('/{prospect}', [ShowProspect::class, 'inShop'])->name('show');
        Route::get('/{prospect}/edit', [EditProspect::class, 'inShop'])->name('edit');
        Route::get('/{prospect}/delete', [RemoveProspect::class, 'inShop'])->name('remove');
    });


    Route::prefix('mailroom')->as('mailroom.')->group(function () {
        Route::get('', ['icon' => 'fa-envelope', 'label' => 'mailroom'])->uses([ShowMailroomDashboard::class, 'inShop'])->name('dashboard');

        Route::prefix('templates')->as('templates.')->group(function () {
            Route::get('{emailTemplate}', [ShowEmailTemplate::class, 'inShop'])->name('show');
            Route::get('{emailTemplate}/workshop', ShowEmailTemplateWorkshop::class)->name('workshop');
        });
    });


    Route::prefix('appointments')->as('appointments.')->group(function () {
        Route::get('/', ['icon' => 'fa-handshake', 'label' => 'appointment'])->uses([IndexAppointments::class, 'inShop'])->name('index');
        Route::get('/create', ['icon' => 'fa-handshake', 'label' => 'appointment'])->uses([CreateAppointment::class, 'inShop'])->name('create');

        Route::get('/{appointment}', ['icon' => 'fa-handshake', 'label' => 'appointment'])->uses(ShowAppointment::class)->name('show');
        Route::get('/{appointment}/edit', ['icon' => 'fa-handshake', 'label' => 'appointment'])->uses(EditAppointment::class)->name('edit');
    });
});
