<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 06 Mar 2023 18:42:58 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\Organisation\Accounting\Invoice\ExportInvoice;
use App\Actions\Organisation\Accounting\Invoice\ExportInvoices;
use App\Actions\Organisation\Accounting\Invoice\IndexInvoices;
use App\Actions\Organisation\Accounting\Invoice\ShowInvoice;
use App\Actions\Organisation\Accounting\Payment\ExportPayments;
use App\Actions\Organisation\Accounting\Payment\UI\CreatePayment;
use App\Actions\Organisation\Accounting\Payment\UI\EditPayment;
use App\Actions\Organisation\Accounting\Payment\UI\IndexPayments;
use App\Actions\Organisation\Accounting\Payment\UI\ShowPayment;
use App\Actions\Organisation\Accounting\PaymentAccount\ExportPaymentAccounts;
use App\Actions\Organisation\Accounting\PaymentAccount\UI\CreatePaymentAccount;
use App\Actions\Organisation\Accounting\PaymentAccount\UI\IndexPaymentAccounts;
use App\Actions\Organisation\Accounting\PaymentAccount\UI\ShowPaymentAccount;
use App\Actions\Organisation\Accounting\PaymentServiceProvider\ExportPaymentServiceProviders;
use App\Actions\Organisation\Accounting\PaymentServiceProvider\UI\CreatePaymentServiceProvider;
use App\Actions\Organisation\Accounting\PaymentServiceProvider\UI\EditPaymentServiceProvider;
use App\Actions\Organisation\Accounting\PaymentServiceProvider\UI\IndexPaymentServiceProviders;
use App\Actions\Organisation\Accounting\PaymentServiceProvider\UI\RemovePaymentServiceProvider;
use App\Actions\Organisation\Accounting\PaymentServiceProvider\UI\ShowPaymentServiceProvider;
use App\Actions\UI\Organisation\Accounting\AccountingDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', AccountingDashboard::class)->name('dashboard');

Route::get('/providers/{paymentServiceProvider}/accounts/{paymentAccount}/payments/create', [IndexPayments::class, 'inPaymentAccountInPaymentServiceProvider'])->name('payment-service-providers.show.payment-accounts.show.payments.create');
Route::get('/providers/{paymentServiceProvider}/payments/create', [IndexPayments::class, 'inPaymentServiceProvider'])->name('payment-service-providers.show.payments.create');


Route::get('/providers', IndexPaymentServiceProviders::class)->name('payment-service-providers.index');
Route::get('/providers/create', CreatePaymentServiceProvider::class)->name('payment-service-providers.create');
Route::get('/providers/export', ExportPaymentServiceProviders::class)->name('payment-service-providers.export');
Route::get('/providers/{paymentServiceProvider}', ShowPaymentServiceProvider::class)->name('payment-service-providers.show');
Route::get('/providers/{paymentServiceProvider}/edit', EditPaymentServiceProvider::class)->name('payment-service-providers.edit');
Route::get('/providers/{paymentServiceProvider}/delete', RemovePaymentServiceProvider::class)->name('payment-service-providers.remove');
Route::get('/providers/{paymentServiceProvider}/accounts', [IndexPaymentAccounts::class, 'inPaymentServiceProvider'])->name('payment-service-providers.show.payment-accounts.index');
Route::get('/providers/{paymentServiceProvider}/accounts/{paymentAccount}', [ShowPaymentAccount::class, 'inPaymentServiceProvider'])->name('payment-service-providers.show.payment-accounts.show');
Route::get('/providers/{paymentServiceProvider}/accounts/{paymentAccount}/payments', [IndexPayments::class, 'inPaymentAccountInPaymentServiceProvider'])->name('payment-service-providers.show.payment-accounts.show.payments.index');
Route::get('/providers/{paymentServiceProvider}/accounts/{paymentAccount}/payments/{payment}', [ShowPayment::class, 'inPaymentAccountInPaymentServiceProvider'])->name('payment-service-providers.show.payment-accounts.show.payments.show');
Route::get('/providers/{paymentServiceProvider}/accounts/{paymentAccount}/payments/{payment}/edit', [EditPayment::class, 'inPaymentAccountInPaymentServiceProvider'])->name('payment-service-providers.show.payment-accounts.show.payments.edit');
Route::get('/providers/{paymentServiceProvider}/payments', [IndexPayments::class, 'inPaymentServiceProvider'])->name('payment-service-providers.show.payments.index');
Route::get('/providers/{paymentServiceProvider}/payments/{payment}/edit', [EditPayment::class, 'inPaymentServiceProvider'])->name('payment-service-providers.show.payments.edit');
Route::get('/providers/{paymentServiceProvider}/payments/{payment}', [ShowPayment::class, 'inPaymentServiceProvider'])->name('payment-service-providers.show.payments.show');

Route::get('/accounts/create', CreatePaymentAccount::class)->name('payment-accounts.create');
Route::get('/accounts/export', ExportPaymentAccounts::class)->name('payment-accounts.export');
Route::get('/accounts/{paymentAccount}/payments/create', [CreatePayment::class, 'inPaymentAccount'])->name('payment-accounts.show.payments.create');
//Route::get('/payments/create', CreatePayment::class)->name('payments.create');
Route::get('/accounts', IndexPaymentAccounts::class)->name('payment-accounts.index');
Route::get('/accounts/{paymentAccount}', ShowPaymentAccount::class)->name('payment-accounts.show');
Route::get('/accounts/{paymentAccount}/payments', [IndexPayments::class, 'inPaymentAccount'])->name('payment-accounts.show.payments.index');
Route::get('/accounts/{paymentAccount}/payments/{payment}', [ShowPayment::class, 'inPaymentAccount'])->name('payment-accounts.show.payments.show');
Route::get('/accounts/{paymentAccount}/payments/{payment}/edit', [EditPayment::class, 'inPaymentAccount'])->name('payment-accounts.show.payments.edit');

Route::get('/payments/export', ExportPayments::class)->name('payments.export');
Route::get('/payments', IndexPayments::class)->name('payments.index');
Route::get('/payments/{payment}', [ShowPayment::class, 'inOrganisation'])->name('payments.show');
Route::get('/payments/{payment}/edit', [EditPayment::class, 'inOrganisation'])->name('payments.edit');
Route::get('/invoices/{invoice}/export', ExportInvoice::class)->name('invoices.download');
Route::get('/invoices/export', ExportInvoices::class)->name('invoices.export');
Route::get('/invoices', IndexInvoices::class)->name('invoices.index');
Route::get('/invoices/{invoice}', ShowInvoice::class)->name('invoices.show');
