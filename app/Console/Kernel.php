<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 08 Jul 2023 21:41:03 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Console;

use App\Actions\Helpers\AwsEmail\CheckPendingSenderEmailVerifications;
use App\Actions\Mail\Mailshot\SendMailshotScheduled;
use App\Actions\Portfolio\Banner\FetchBannerAnalytics;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        if ($this->app->environment('local')) {
            $schedule->command('telescope:prune')->daily();
        }

        $schedule->job(FetchBannerAnalytics::makeJob())
            ->name(FetchBannerAnalytics::class)
            ->daily();
        $schedule->job(SendMailshotScheduled::makeJob())
            ->name(SendMailshotScheduled::class)
            ->everyMinute();
        $schedule->job(CheckPendingSenderEmailVerifications::makeJob())
            ->name(CheckPendingSenderEmailVerifications::class)
            ->everyFourHours();

    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
