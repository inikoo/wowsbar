<?php


use App\Actions\Organisation\Accounting\PaymentGateway\Xendit\Webhook\HandleWebhookNotification;

Route::middleware('webhooks-api')->group(function () {
    Route::post('xendit/callback', HandleWebhookNotification::class)->name('webhook.xendit.notification');
});
