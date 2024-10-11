<?php

use App\Actions\Accounting\PaymentServiceProvider\UI\IndexPaymentServiceProviders;

Route::get('/providers', IndexPaymentServiceProviders::class)->name('payment-service-providers.index');
