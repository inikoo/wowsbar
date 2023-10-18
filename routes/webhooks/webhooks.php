<?php


use App\Actions\Accounting\PaymentGateway\Xendit\Webhook\HandleWebhookNotification;

Route::group([], function () {
    Route::post('xendit/callback', HandleWebhookNotification::class)->name('xendit.notification');
});
