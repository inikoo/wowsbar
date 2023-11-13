<?php


use App\Actions\Mail\Mailshot\UI\UnsubscribeMailshot;

Route::get('unsubscribe/{dispatchedEmail:ulid}', UnsubscribeMailshot::class)->name('mailshot.unsubscribe');
