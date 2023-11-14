<?php


use App\Actions\Mail\Mailshot\Unsubscribe\UnsubscribeMailshot;

Route::get('unsubscribe/{dispatchedEmail:ulid}', UnsubscribeMailshot::class)->name('mailshot.unsubscribe');
