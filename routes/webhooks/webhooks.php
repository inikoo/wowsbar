<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 09 Nov 2023 00:15:57 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\Accounting\PaymentGateway\Xendit\Webhook\HandleWebhookNotification;
use App\Actions\Mail\Mailshot\UnsubscribeMailshot;
use App\Actions\Mail\Notifications\GetSnsNotification;

Route::group([], function () {
    Route::any('sns', GetSnsNotification::class)->name('sns');
    Route::post('xendit/callback', HandleWebhookNotification::class)->name('xendit.notification');
    Route::post('unsubscribe/{dispatchedEmail:ulid}', UnsubscribeMailshot::class)->name('mailshot.unsubscribe');
});
