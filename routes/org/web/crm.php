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
use App\Actions\CRM\Customer\Mailshots\UI\CreateCustomersMailshot;
use App\Actions\CRM\Customer\Mailshots\UI\IndexCustomerMailshots;
use App\Actions\CRM\Customer\Newsletters\UI\IndexCustomerNewsletters;
use App\Actions\CRM\Customer\Queries\UI\IndexCustomerQueries;
use App\Actions\CRM\Customer\Surveys\UI\CreateCustomerSurvey;
use App\Actions\CRM\Customer\Surveys\UI\IndexCustomerSurveys;
use App\Actions\CRM\Customer\Surveys\UI\ShowCustomerSurvey;
use App\Actions\CRM\Customer\Tags\UI\IndexCustomerTags;
use App\Actions\CRM\Customer\UI\CreateCustomer;
use App\Actions\CRM\Customer\UI\EditCustomer;
use App\Actions\CRM\Customer\UI\IndexCustomers;
use App\Actions\CRM\Customer\UI\RemoveCustomer;
use App\Actions\CRM\Customer\UI\ShowCustomer;
use App\Actions\CRM\CustomerWebsite\UI\CreateCustomerWebsite;
use App\Actions\CRM\CustomerWebsite\UI\EditCustomerWebsite;
use App\Actions\CRM\CustomerWebsite\UI\IndexCustomerWebsites;
use App\Actions\CRM\CustomerWebsite\UI\ShowCustomerWebsite;
use App\Actions\CRM\ShipperAccount\UI\CreateShipperAccount;
use App\Actions\CRM\ShipperAccount\UI\IndexShipperAccounts;
use App\Actions\CRM\User\UI\CreateOrgCustomerUser;
use App\Actions\CRM\User\UI\EditOrgCustomerUser;
use App\Actions\CRM\User\UI\IndexOrgCustomerUsers;
use App\Actions\CRM\User\UI\ShowOrgCustomerUser;
use App\Actions\Helpers\Uploads\DownloadUploads;
use App\Actions\Helpers\Uploads\HistoryUploads;
use App\Actions\Helpers\Uploads\UI\ShowUploads;
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
use App\Actions\Mail\DispatchedEmail\UI\ShowDispatchedEmail;
use App\Actions\Mail\EmailTemplate\UI\ShowEmailTemplate;
use App\Actions\Mail\EmailTemplate\UI\ShowEmailTemplateWorkshop;
use App\Actions\Mail\Mailshot\GetEstimateRecipientsWhileCreatingMailshot;
use App\Actions\Mail\Mailshot\UI\EditProspectMailshot;
use App\Actions\Mail\Mailshot\UI\ShowProspectMailshot;
use App\Actions\Mail\Mailshot\UI\ShowProspectMailshotWorkshop;
use App\Actions\Subscriptions\CustomerSocialAccount\UI\ShowCustomerSocialAccount;
use App\Actions\SysAdmin\UI\CRM\ShowCRMDashboard;
use App\Actions\SysAdmin\UI\CRM\ShowMailroomDashboard;

Route::get('/', function () {
    return redirect('/crm/dashboard');
});
Route::get('/dashboard', ['icon' => 'fa-envelope', 'label' => 'show crm dashboard'])->uses([ShowCRMDashboard::class, 'inOrganisation'])->name('dashboard');

Route::get('customers', ['icon' => 'fa-envelope', 'label' => 'customers'])->uses(IndexCustomers::class)->name('customers.index');
Route::get('customers/create', ['icon' => 'fa-envelope', 'label' => 'create customer'])->uses(CreateCustomer::class)->name('customers.create');

Route::prefix('customers/{customer}')->as('customers.')->group(function () {
    Route::get('', ['icon' => 'fa-envelope', 'label' => 'show customer'])->uses(ShowCustomer::class)->name('show');
    Route::get('edit', ['icon' => 'fa-envelope', 'label' => 'edit customer'])->uses([EditCustomer::class, 'inOrganisation'])->name('edit');
    Route::get('delete', ['icon' => 'fa-envelope', 'label' => 'remove customer'])->uses(RemoveCustomer::class)->name('remove');
    Route::get('customer-users', ['icon' => 'fa-envelope', 'label' => 'customer users'])->uses([IndexOrgCustomerUsers::class, 'inCustomer'])->name('show.customer-users.index');
    Route::get('customer-users/create', ['icon' => 'fa-envelope', 'label' => 'create customer user'])->uses([CreateOrgCustomerUser::class, 'inCustomer'])->name('show.customer-users.create');
    Route::get('customer-users/{user}', ['icon' => 'fa-envelope', 'label' => 'show customer user'])->uses([ShowOrgCustomerUser::class, 'inCustomer'])->name('show.customer-users.show');
    Route::get('customer-users/{user}/edit', ['icon' => 'fa-envelope', 'label' => 'edit customer user'])->uses([EditOrgCustomerUser::class, 'inCustomer'])->name('show.customer-users.edit');
});

Route::prefix('prospects')->as('prospects.')->group(function () {
    Route::get('/', ['icon' => 'fa-envelope', 'label' => 'prospects'])->uses(IndexProspects::class)->name('index');
    Route::get('/mailshots', ['icon' => 'fa-envelope', 'label' => 'prospect mailshots'])->uses([IndexProspectMailshots::class, 'inShop'])->name('mailshots.index');

    Route::get('/{prospect}', ['icon' => 'fa-envelope', 'label' => 'show prospect'])->uses(ShowProspect::class)->name('show');
    Route::get('/{prospect}/delete', ['icon' => 'fa-envelope', 'label' => 'crm dashboard'])->uses(RemoveProspect::class)->name('remove');

    Route::get('/histories/uploads', ['icon' => 'fa-envelope', 'label' => 'history upload prospect'])->uses([HistoryUploads::class, 'inProspect'])->name('uploads.history');

    Route::prefix('uploads')->as('uploads.')->group(function () {
        Route::get('{upload}', ShowUploads::class)->name('show');
    });
});

Route::prefix('shop/{shop}')->as('shop.')->group(function () {
    Route::get('/', function ($shop) {
        return redirect()->route('org.crm.shop.dashboard', [$shop]);
    });
    Route::get('/dashboard', ['icon' => 'fa-envelope', 'label' => 'crm dashboard'])->uses([ShowCRMDashboard::class, 'inShop'])->name('dashboard');

    Route::prefix('customers')->as('customers.')->group(function () {
        Route::get('/', ['icon' => 'fa-envelope', 'label' => 'customers'])->uses([IndexCustomers::class, 'inShop'])->name('index');
        Route::get('create', ['icon' => 'fa-envelope', 'label' => 'create customer'])->uses([CreateCustomer::class, 'inShop'])->name('create');

        Route::prefix('lists')->as('lists.')->group(function () {
            Route::get('/', ['icon' => 'fa-envelope', 'label' => 'customer lists'])->uses([IndexCustomerQueries::class, 'inShop'])->name('index');
            Route::get('{query}', ['icon' => 'fa-envelope', 'label' => 'show customer list'])->uses(ShowProspectQuery::class)->name('show');
        });

        Route::prefix('tags')->as('tags.')->group(function () {
            Route::get('/', ['icon' => 'fa-envelope', 'label' => 'customer tags'])->uses([IndexCustomerTags::class, 'inShop'])->name('index');
            Route::get('{tag}', ['icon' => 'fa-envelope', 'label' => 'show customer tag'])->uses([ShowProspectTag::class, 'inShop'])->name('show');
        });

        Route::prefix('surveys')->as('surveys.')->group(function () {
            Route::get('/', ['icon' => 'fa-envelope', 'label' => 'customer surveys'])->uses([IndexCustomerSurveys::class, 'inShop'])->name('index');
            Route::get('/create', ['icon' => 'fa-envelope', 'label' => 'create customer surveys'])->uses([CreateCustomerSurvey::class, 'inShop'])->name('create');
            Route::get('/{survey}', ['icon' => 'fa-envelope', 'label' => 'show customer surveys'])->uses([ShowCustomerSurvey::class, 'inShop'])->name('show');
        });

        Route::prefix('mailshots')->as('mailshots.')->group(function () {
            Route::get('', ['icon' => 'fa-envelope', 'label' => 'mailshots'])->uses([IndexCustomerMailshots::class, 'inShop'])->name('index');

            // TODO Need to create the customers actions
            Route::get('create', ['icon' => 'fa-envelope', 'label' => 'create mailshot'])->uses([CreateCustomersMailshot::class, 'inShop'])->name('create');
            Route::get('{tag}', ['icon' => 'fa-envelope', 'label' => 'show customer tag'])->uses([ShowProspectTag::class, 'inShop'])->name('tags.show');
            Route::get('{mailshot}/edit', ['icon' => 'fa-envelope', 'label' => 'edit mailshot'])->uses(EditProspectMailshot::class)->name('edit');
            Route::get('{mailshot}/workshop', ['icon' => 'fa-envelope', 'label' => 'workshop mailshot'])->uses(ShowProspectMailshotWorkshop::class)->name('workshop');
            Route::get('{mailshot}', ['icon' => 'fa-envelope', 'label' => 'show mailshot'])->uses(ShowProspectMailshot::class)->name('show');
            Route::get('{mailshot}/recipients/{dispatchedEmail:id}', ['icon' => 'fa-envelope', 'label' => 'show dispatched email'])->uses(ShowDispatchedEmail::class)->name('show.recipients.show');
        });

        Route::prefix('newsletters')->as('newsletters.')->group(function () {
            Route::get('', ['icon' => 'fa-envelope', 'label' => 'newsletters'])->uses([IndexCustomerNewsletters::class, 'inShop'])->name('index');

            // TODO Need to create the customers actions
            Route::get('create', ['icon' => 'fa-envelope', 'label' => 'create newsletter'])->uses([CreateCustomersMailshot::class, 'inShop'])->name('create');
        });

        Route::prefix('{customer}')->group(function () {
            Route::get('', ['icon' => 'fa-envelope', 'label' => 'show customer'])->uses([ShowCustomer::class, 'inShop'])->name('show');
            Route::get('/edit', ['icon' => 'fa-envelope', 'label' => 'edit customer'])->uses([EditCustomer::class, 'inShop'])->name('edit');

            Route::get('/social-accounts/{customerSocialAccount}', ['icon' => 'fa-envelope', 'label' => 'show social account'])->uses([ShowCustomerSocialAccount::class, 'inCustomer'])->name('show.customer-social-accounts.show');

            Route::get('/users', ['icon' => 'fa-envelope', 'label' => 'customer users'])->uses([IndexOrgCustomerUsers::class, 'inCustomerInShop'])->name('show.customer-users.index');
            Route::get('/users/create', ['icon' => 'fa-envelope', 'label' => 'create customer user'])->uses([CreateOrgCustomerUser::class, 'inCustomerInShop'])->name('show.customer-users.create');
            Route::get('/users/{customerUser}', ['icon' => 'fa-envelope', 'label' => 'show customer user'])->uses([ShowOrgCustomerUser::class, 'inCustomerInShop'])->name('show.customer-users.show');
            Route::get('users/{customerUser}/edit', ['icon' => 'fa-envelope', 'label' => 'edit customer user'])->uses([EditOrgCustomerUser::class, 'inCustomerInShop'])->name('show.customer-users.edit');

            Route::get('/customer-websites', ['icon' => 'fa-envelope', 'label' => 'create customer website'])->uses([IndexCustomerWebsites::class, 'inCustomerInShop'])->name('show.customer-websites.index');
            Route::get('/customer-websites/create', ['icon' => 'fa-envelope', 'label' => 'create customer website'])->uses([CreateCustomerWebsite::class, 'inCustomerInShop'])->name('show.customer-websites.create');
            Route::get('/customer-websites/{customerWebsite}', ['icon' => 'fa-envelope', 'label' => 'show customer website'])->uses([ShowCustomerWebsite::class, 'inCustomerInShop'])->name('show.customer-websites.show');
            Route::get('/customer-websites/{customerWebsite}/edit', ['icon' => 'fa-envelope', 'label' => 'edit customer website'])->uses([EditCustomerWebsite::class, 'inCustomerInShop'])->name('show.customer-websites.edit');

            Route::prefix('shipper-accounts')->as('shipper-accounts.')->group(function () {
                Route::get('/', ['icon' => 'fa-envelope', 'label' => 'shipper accounts'])->uses([IndexShipperAccounts::class, 'inCustomerInShop'])->name('index');
                Route::get('/create', ['icon' => 'fa-envelope', 'label' => 'create shipper account'])->uses([CreateShipperAccount::class, 'inCustomerInShop'])->name('create');
                Route::get('/{shipperAccount}', ['icon' => 'fa-envelope', 'label' => 'show shipper account'])->uses([ShowOrgCustomerUser::class, 'inCustomerInShop'])->name('show');
                //                Route::get('/{shipperAccount}/edit', ['icon' => 'fa-envelope', 'label' => 'edit shipper account'])->uses([EditOrgCustomerUser::class, 'inCustomerInShop'])->name('edit');
            });
        });
    });


    Route::prefix('prospects')->as('prospects.')->group(function () {
        Route::get('/', ['icon' => 'fa-envelope', 'label' => 'prospects'])->uses([IndexProspects::class, 'inShop'])->name('index');
        Route::get('/create', ['icon' => 'fa-envelope', 'label' => 'create prospect'])->uses([CreateProspect::class, 'inShop'])->name('create');

        Route::get('/export', ExportProspects::class)->name('export');

        Route::prefix('lists')->as('lists.')->group(function () {
            Route::get('/', ['icon' => 'fa-envelope', 'label' => 'prospect lists'])->uses([IndexProspectQueries::class, 'inShop'])->name('index');
            Route::get('/create', ['icon' => 'fa-envelope', 'label' => 'create prospect list'])->uses([CreateProspectQuery::class, 'inShop'])->name('create');
            Route::get('/{query}/edit', ['icon' => 'fa-envelope', 'label' => 'edit prospect list'])->uses([EditProspectQuery::class, 'inShop'])->name('edit');
            Route::get('{query}', ['icon' => 'fa-envelope', 'label' => 'show prospect list'])->uses(ShowProspectQuery::class)->name('show');
        });

        Route::prefix('tags')->as('tags.')->group(function () {
            Route::get('/', ['icon' => 'fa-envelope', 'label' => 'prospect tags'])->uses([IndexProspectTags::class, 'inShop'])->name('index');
            Route::get('/create', ['icon' => 'fa-envelope', 'label' => 'create prospect tag'])->uses([CreateProspectTag::class, 'inShop'])->name('create');
            Route::get('{tag}/edit', ['icon' => 'fa-envelope', 'label' => 'edit prospect tag'])->uses([EditProspectTag::class, 'inShop'])->name('edit');
            Route::get('{tag}', ['icon' => 'fa-envelope', 'label' => 'show prospect tag'])->uses([ShowProspectTag::class, 'inShop'])->name('show');
        });

        Route::prefix('mailshots')->as('mailshots.')->group(function () {
            Route::get('estimated-recipients', GetEstimateRecipientsWhileCreatingMailshot::class)->name('estimated-recipients');
            Route::get('', ['icon' => 'fa-envelope', 'label' => 'mailshots'])->uses([IndexProspectMailshots::class, 'inShop'])->name('index');
            Route::get('create', ['icon' => 'fa-envelope', 'label' => 'create mailshot'])->uses([CreateProspectsMailshot::class, 'inShop'])->name('create');
            Route::get('{mailshot}/edit', ['icon' => 'fa-envelope', 'label' => 'edit mailshot'])->uses(EditProspectMailshot::class)->name('edit');
            Route::get('{mailshot}/workshop', ['icon' => 'fa-envelope', 'label' => 'workshop mailshot'])->uses(ShowProspectMailshotWorkshop::class)->name('workshop');
            Route::get('{mailshot}', ['icon' => 'fa-envelope', 'label' => 'show mailshot'])->uses(ShowProspectMailshot::class)->name('show');
            Route::get('{mailshot}/recipients/{dispatchedEmail:id}', ['icon' => 'fa-envelope', 'label' => 'show dispatched email'])->uses(ShowDispatchedEmail::class)->name('show.recipients.show');
        });

        Route::get('/{prospect}', ['icon' => 'fa-envelope', 'label' => 'show prospect'])->uses([ShowProspect::class, 'inShop'])->name('show');
        Route::get('/{prospect}/edit', ['icon' => 'fa-envelope', 'label' => 'edit prospect'])->uses([EditProspect::class, 'inShop'])->name('edit');
        Route::get('/{prospect}/delete', ['icon' => 'fa-envelope', 'label' => 'remove prospect'])->uses([RemoveProspect::class, 'inShop'])->name('remove');

        Route::prefix('uploads')->as('uploads.')->group(function () {
            Route::get('{upload}/download', ['icon' => 'fa-envelope', 'label' => 'download uploads'])->uses(DownloadUploads::class)->name('download');
            Route::get('{upload}', [ShowUploads::class, 'inShop'])->name('show');
        });
    });


    Route::prefix('mailroom')->as('mailroom.')->group(function () {
        Route::get('', ['icon' => 'fa-envelope', 'label' => 'mailroom'])->uses([ShowMailroomDashboard::class, 'inShop'])->name('dashboard');

        Route::prefix('templates')->as('templates.')->group(function () {
            Route::get('{emailTemplate}', ['icon' => 'fa-envelope', 'label' => 'email template'])->uses([ShowEmailTemplate::class, 'inShop'])->name('show');
            Route::get('{emailTemplate}/workshop', ['icon' => 'fa-envelope', 'label' => 'email template workshop'])->uses(ShowEmailTemplateWorkshop::class)->name('workshop');
        });
    });


    Route::prefix('appointments')->as('appointments.')->group(function () {
        Route::get('/', ['icon' => 'fa-handshake', 'label' => 'appointment'])->uses([IndexAppointments::class, 'inShop'])->name('index');
        Route::get('/create', ['icon' => 'fa-handshake', 'label' => 'appointment'])->uses([CreateAppointment::class, 'inShop'])->name('create');

        Route::get('/{appointment}', ['icon' => 'fa-handshake', 'label' => 'appointment'])->uses(ShowAppointment::class)->name('show');
        Route::get('/{appointment}/edit', ['icon' => 'fa-handshake', 'label' => 'appointment'])->uses(EditAppointment::class)->name('edit');
    });
});
