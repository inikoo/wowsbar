<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 08 Jul 2023 21:41:03 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Console;

use App\Actions\Helpers\AwsEmail\CheckSenderEmailVerification;
use App\Actions\Mail\Mailshot\SendMailshotScheduled;
use App\Actions\Portfolio\Banner\FetchBannerAnalytics;
use App\Models\Mail\SenderEmail;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        if ($this->app->environment('local')) {
            $schedule->command('telescope:prune')->daily();
        }

         $schedule->call(function () {
             FetchBannerAnalytics::dispatch();
         })->daily();
        $schedule->call(function () {
            SendMailshotScheduled::dispatch();
        })->everyMinute();
        $schedule->call(function () {
            SenderEmail::whereNull('verified_at')->get()->each(function (SenderEmail $senderEmail) {
                CheckSenderEmailVerification::dispatch($senderEmail);
            });
        })->everyMinute();
    }


    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
