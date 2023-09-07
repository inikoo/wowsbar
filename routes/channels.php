<?php

use App\Models\Tenancy\Tenant;
use App\Models\WebsiteUploadRecord;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('uploads.{tenantId}', function (Tenant $tenant, int $tenantId) {
    return $tenant->id === $tenantId;
});
